<?php

class FileList
{
    public function getDirectoryList(string $path)
    {
        $dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), TRUE);

        foreach ($dir as $item)
        {
            echo $item->getType() . ' ' . str_repeat('   ', $dir->getDepth()) . $item . "<br>\n";
        }
    }
}

$path = '/Users/lexx/PhpstormProjects/mvc';

if (isset($argv[1]))
{
        $path = $argv[1];
}

(new FileList)->getDirectoryList($path);

