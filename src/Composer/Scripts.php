<?php


namespace Tendril\Composer;


use Composer\Script\Event;


class Scripts
{
    public static function installTheme(Event $event)
    {
        $io = $event->getIO();
        $vendor_dir = $event->getComposer()->getConfig()->get('vendor-dir');
        $source_dir = $vendor_dir . '/johannez/tendril/theme';
        


        $name_accepted = false;

        while(!$name_accepted) {
            $name = $io->askAndValidate("Enter the name of theme: ", function($answer) {
                if (empty($answer)) {
                    throw new \Exception('You have to enter a name to proceed.');
                }

                return $answer;
            });

            $name_accepted = $io->askConfirmation("Is `$name` correct? [yes] ");
        }

        $name_sanitized = preg_replace('/\W+/','',strtolower($name));
        $location = $io->ask("Enter installation path: [/wp-content/themes/$name_sanitized]", "/wp-content/themes/$name_sanitized");
        $author = $io->ask("Enter author (optional): [Jane Doe, Organization]");

        $target_dir = $vendor_dir. '/..' . $location;

        // Copy whole folder over.
        self::copyDirectory($source_dir, $target_dir);

        // Change Style.css and replace name and author.
        $style_css = $target_dir . '/style.css';
        if (file_exists($style_css)) {
            $content = file_get_contents($style_css);

            $content = str_replace('%NAME%', $name, $content);
            $content = str_replace('%AUTHOR%', $author, $content);

            file_put_contents($style_css, $content);
        }
    }


    public static function copyDirectory($source, $destination)
    {
        if (file_exists($source)) {
            $dir = opendir($source);
            @mkdir($destination);
            while(false !== ( $file = readdir($dir)) ) {
                if (( $file != '.' ) && ( $file != '..' )) {
                    if ( is_dir($source . '/' . $file) ) {
                        self::copyDirectory($source . '/' . $file,$destination . '/' . $file);
                    }
                    else {
                        copy($source . '/' . $file, $destination . '/' . $file);
                    }
                }
            }
            closedir($dir);
        }
        
    }
}
