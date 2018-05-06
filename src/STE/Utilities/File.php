<?php
namespace STE\Utilities;

class File
{
    /**
     * Given the filename, opens a file and returns a string with its content
     *
     * 
     * @param string $argv - Array of arguments passed to script
     *
     * @return string  returns the contents of the file, or prints an error message and stops execution
     */
    public function openFile ($argv) {
        // check if we got a filename from cli
        if ($argv[1]) {
            $fileName = $argv[1];
        } else {
            printf('Please specify a template name while calling the script.' . PHP_EOL);
            printf('E.g.: php index.php template.tmpl.' . PHP_EOL);
            exit;
        }

        // open file and put content in a string
        $handle = file_get_contents($fileName);

        // empty file, or any other problem
        if (!$handle) {
            printf('Error loading file.' . PHP_EOL);
            exit;
        }

        //return a string containing the file contents
        return $handle;
    }
}
