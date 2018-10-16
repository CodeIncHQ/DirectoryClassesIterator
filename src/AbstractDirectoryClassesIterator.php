<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material is strictly forbidden unless prior    |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     16/10/2018
// Project:  AritasIntranet
//
declare(strict_types=1);
namespace CodeInc\DirectoryClassesIterator;
use CodeInc\DirectoryClassesIterator\Exceptions\NotADirectoryException;


/**
 * Class AbstractDirectoryClassesIterator
 *
 * @package CodeInc\DirectoryClassesIterator
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractDirectoryClassesIterator implements \IteratorAggregate
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string[]
     */
    private $extensions;

    /**
     * AbstractDirectoryClassesIterator constructor.
     *
     * @param string $path
     * @param array $extensions
     */
    public function __construct(string $path, array $extensions = ['php'])
    {
        if (!is_dir($path)) {
            throw new NotADirectoryException($path);
        }
        $this->path = $path;
        $this->extensions = $extensions;
    }

    /**
     * Returns the directory iterator.
     *
     * @return \Iterator|\SplFileInfo[]
     */
    abstract protected function getDirectoryIterator():\Iterator;

    /**
     * Returns the directory path.
     *
     * @return string
     */
    public function getPath():string
    {
        return $this->path;
    }

    /**
     * Returns the allowed classes extensions.
     *
     * @return string[]
     */
    public function getExtensions():array
    {
        return $this->extensions;
    }

    /**
     * Sets the allowed classes extensions.
     *
     * @param string[] $extensions
     */
    public function setExtensions(array $extensions):void
    {
        $this->extensions = $extensions;
    }

    /**
     * @inheritdoc
     * @return \ReflectionClass[]|\Generator
     * @throws \ReflectionException
     */
    public function getIterator():\Generator
    {
        $loadedFiles = [];
        /** @var \SplFileInfo $entry */
        foreach ($this->getDirectoryIterator() as $entry) {
            if (in_array($entry->getExtension(), $this->extensions)) {
                $loadedFiles[] = $entry->getPathname();
                /** @noinspection PhpIncludeInspection */
                include_once $entry->getPathname();
            }
        }
        foreach (get_declared_classes() as $declaredClass) {
            $reflectionClass = new \ReflectionClass($declaredClass);
            if (in_array($reflectionClass->getFileName(), $loadedFiles)) {
                yield $reflectionClass;
            }
        }
    }
}