<?php

// Usage: php digicol_twitter_archive_to_xml.php tweets/data/js/tweets/*.js
//
// Requires PHP 5.2 or later
//
// Licensed under the PHP License
//
// Use at your own risk!
//
// Tim Strehle <tim@digicol.de>


require_once('digicol_console_options.class.php');


class Digicol_Twitter_Archive_To_Xml
{
    protected $exit_on_unknown_options = true;
    
    
    public function getHelpText()
    {
        return <<<EOT

  Convert the JSON archive you can download from Twitter to XML.

  Set <file> to "-" to read file names or XML from STDIN. (This allows you to use
  digicol_twitter_archive_to_xml.php in conjunction with "find".)

  Usage: php digicol_twitter_archive_to_xml.php [ OPTIONS ] <file> [<file> ...]

        --write            Instead of XML output to STDOUT, write it into a file
                           next to the JSON file, named like the JSON file but
                           with .xml appended. WARNING: This will OVERWRITE
                           an existing .xml file with the same name!
                           
    -h, --help             Display this help message and exit.

  Copyright 2013 by Digital Collections Verlagsgesellschaft mbH.
  Report bugs to: <tim@digicol.de>


EOT;

    }


    public function main($script_filename, $argv)
    {
        $this->parseOptions($argv);
        $this->determineAction();       
        $this->executeAction();
    }


    public function parseOptions($argv)
    {
        $getopt = new Digicol_Console_Options($this->getOptionDefinitions());

        $this->options = $getopt->parse($argv, $this->exit_on_unknown_options);
        
        if (is_string($this->options))
        {
            fwrite(STDERR, sprintf("Unknown option \"%s\". Try -h for more information.\n", $this->options));
            exit(1);
        }
    }


    protected function getOptionDefinitions()
    {
        return array
        (
            array( 'write'       ,  0  ),
            array( 'help, h'     ,  0  )
        );
    }


    public function determineAction()
    {
        $this->action = '';
        
        if (isset($this->options[ 'help' ]))
        {
            $this->action = 'help';
            return;
        }
            
        if (count($this->options[ '_' ]) > 0)
            $this->action = 'convert';
    }
    
    
    public function executeAction()
    {
        if ($this->action == '')
        {
            fwrite(STDERR, "Nothing to do. Try -h for more information.\n");
            exit(1);
        }

        $method = 'executeAction_' . $this->action;
        
        $this->$method();
    }


    protected function executeAction_help()
    {
        fwrite(STDOUT, $this->getHelpText());
    }


    protected function executeAction_convert()
    {
        // Read from STDIN?

        if ($this->options[ '_' ][ 0 ] === '-')
        {
            while (! feof(STDIN))
            {
                $filename = trim(fgets(STDIN));

                if ($filename === '')
                    continue;
                    
                $this->convertFile($filename);
            }
        }
        else
        {
            foreach ($this->options[ '_' ] as $filename)
                $this->convertFile($filename);
        }
    }
    
    
    protected function convertFile($filename)
    {
        $lines = file($filename);

        // For Twitter data .js JSON parsing, cut off the first line
        unset($lines[ 0 ]);

        $data = json_decode(implode('', $lines), true);

        $xml = 
            '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . "<tweets>\n"
            . $this->jsonToXml('tweet', $data, 1)
            . "</tweets>\n";

        if (isset($this->options[ 'write' ]))
        {
            $xml_filename = $filename . '.xml';
            
            $ok = file_put_contents($xml_filename, $xml);
            
            if ($ok === false)
            {
                fwrite(STDERR, "ERROR - Could not write <$xml_filename>.\n");
            }
            else
            {
                fwrite(STDOUT, $xml_filename . "\n");
            }
        }
        else
        {
            echo $xml;
        }
    }


    protected function jsonToXml($tag, $data, $level)
    {
        $result = '';
    
        $html_tags = array( 'source' );
    
        if (! is_array($data))
        {
            // Convert boolean values to 0/1
        
            if ($data === true)
                $data = '1';
        
            if ($data === false)
                $data = '0';
        
            if (strlen($data) === 0)
                return sprintf("%s<%s/>\n", str_repeat(' ', $level * 2), $tag);        

            $result .= sprintf
            (
                "%s<%s>%s</%s>\n", 
                str_repeat(' ', $level * 2), 
                $tag, 
                (in_array($tag, $html_tags) ? $data : htmlspecialchars($data)), 
                $tag
            );
        
            // Adding an ISO 8601 timestamp version of "created_at":
            // <created_at>Sat Feb 02 20:57:44 +0000 2013</created_at>
            // += <_created_at_iso>2013-02-02T20:57:44+00:00</_created_at_iso>
        
            if ($tag === 'created_at')
            {
                $tag = '_created_at_iso';
            
                $result .= sprintf
                (
                    "%s<%s>%s</%s>\n", 
                    str_repeat(' ', $level * 2), 
                    $tag, 
                    htmlspecialchars(date('c', strtotime($data))), 
                    $tag
                );
            }

            return $result;        
        }
    
        $keys = array_keys($data);
    
        // Empty array
    
        if (count($keys) === 0)
            return sprintf("%s<%s/>\n", str_repeat(' ', $level * 2), $tag);
        
        // Dumb numeric array detection
    
        if (is_numeric($keys[ 0 ]))
        {
            foreach ($keys as $key)
                $result .= $this->jsonToXml($tag, $data[ $key ], $level);
        }
        else
        {
            $result .= sprintf("%s<%s>\n", str_repeat(' ', $level * 2), $tag);
        
            foreach ($keys as $key)
                $result .= $this->jsonToXml($key, $data[ $key ], $level + 1);
            
            $result .= sprintf("%s</%s>\n", str_repeat(' ', $level * 2), $tag);
        }
    
        return $result;
    }
}


// Execute

$script = new Digicol_Twitter_Archive_To_Xml();
$script->main(__FILE__, $argv);

?>
