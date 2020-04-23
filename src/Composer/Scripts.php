<?php


namespace Tendril\Composer;


use Composer\Script\Event;


class Scripts
{
    public static function installTheme(Event $event)
    {
        $io = $event->getIO();
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $name_accepted = false;

        while(!$name_accepted) {
            $name = $io->askAndValidate("Enter the name of theme: ", function($answer) {
                if (empty($answer)) {
                    throw new \Exception('You have to enter a name to proceed.');
                }

                return preg_replace('/\W+/','',strtolower($answer));
            });

            $name_accepted = $io->askConfirmation("Is `$name` correct? [yes] ");
        }

        $location = $io->ask("Enter installation path: [/wp-content/themes/$name]", "/wp-content/themes/$name");
        $author = $io->ask("Enter author (optional): [Jane Doe, Organization]");

        print "$name\n";
        print "$location\n";
        print "$author\n";
        print "$vendorDir\n";


        // Copy whole folder over.

        // Change Style.css
    }
}
