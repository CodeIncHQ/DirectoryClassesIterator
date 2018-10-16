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
// Project:  DirectoryClassesIterator
//
declare(strict_types=1);
namespace CodeInc\DirectoryClassesIterator\Tests;
use CodeInc\DirectoryClassesIterator\RecursiveDirectoryClassesIterator;
use CodeInc\DirectoryClassesIterator\Tests\Assets\ADotIncClass;
use CodeInc\DirectoryClassesIterator\Tests\Assets\AFirstClass;
use CodeInc\DirectoryClassesIterator\Tests\Assets\AnotherClass;
use CodeInc\DirectoryClassesIterator\Tests\Assets\ASecondClass;
use CodeInc\DirectoryClassesIterator\Tests\Assets\AThirdClass;
use CodeInc\DirectoryClassesIterator\Tests\Assets\SubFolder\AnotherSubClass;
use CodeInc\DirectoryClassesIterator\Tests\Assets\SubFolder\ASubDotIncClass;
use CodeInc\DirectoryClassesIterator\Tests\Assets\SubFolder\ASubFolderClass;
use CodeInc\DirectoryClassesIterator\Tests\Assets\SubFolder\ASubSubFolder\ASubSubFolderClass;
use PHPUnit\Framework\TestCase;


/**
 * Class DirectoryClassesIteratorTest
 *
 * @package CodeInc\DirectoryClassesIterator\Tests
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RecursiveDirectoryClassesIteratorTest extends TestCase
{
    public const ASSETS_PATH = __DIR__.'/Assets';

    public function testListing():void
    {
        $iterator = new RecursiveDirectoryClassesIterator(self::ASSETS_PATH);
        $this->assertInstanceOf(\Traversable::class, $iterator);

        $classes = [];
        foreach ($iterator as $class) {
            $this->assertTrue(
                substr($class->getFileName(), 0, strlen(self::ASSETS_PATH)) == self::ASSETS_PATH
            );
            $this->assertFileExists($class->getFileName());
            $classes[$class->getName()] = $class->getFileName();
        }

        // .php
        $this->assertArrayHasKey(AFirstClass::class, $classes);
        $this->assertArrayHasKey(AnotherClass::class, $classes);
        $this->assertArrayHasKey(ASecondClass::class, $classes);
        $this->assertArrayHasKey(AThirdClass::class, $classes);
        $this->assertContains(__DIR__.'/Assets/AFirstClass.php', $classes);
        $this->assertContains(__DIR__.'/Assets/AnotherClass.php', $classes);
        $this->assertContains(__DIR__.'/Assets/ASecondClass.php', $classes);
        $this->assertContains(__DIR__.'/Assets/AThirdClass.php', $classes);

        // .php recursive
        $this->assertArrayHasKey(ASubFolderClass::class, $classes);
        $this->assertArrayHasKey(AnotherSubClass::class, $classes);
        $this->assertArrayHasKey(ASubSubFolderClass::class, $classes);
        $this->assertContains(__DIR__.'/Assets/SubFolder/ASubFolderClass.php', $classes);
        $this->assertContains(__DIR__.'/Assets/SubFolder/AnotherSubClass.php', $classes);
        $this->assertContains(__DIR__.'/Assets/SubFolder/ASubSubFolder/ASubSubFolderClass.php', $classes);

        // .inc + recursive
        $this->assertArrayNotHasKey(ADotIncClass::class, $classes);
        $this->assertArrayNotHasKey(ASubDotIncClass::class, $classes);
        $this->assertNotContains(__DIR__.'/Assets/ADotIncClass.inc', $classes);
        $this->assertNotContains(__DIR__.'/Assets/SubFolder/ASubDotIncClass.inc', $classes);
    }

    public function testDotInc():void
    {
        $iterator = new RecursiveDirectoryClassesIterator(self::ASSETS_PATH, ['inc']);
        $this->assertInstanceOf(\Traversable::class, $iterator);
        $classes = [];
        foreach ($iterator as $class) {
            $this->assertTrue(
                substr($class->getFileName(), 0, strlen(self::ASSETS_PATH)) == self::ASSETS_PATH
            );
            $this->assertFileExists($class->getFileName());
            $classes[$class->getName()] = $class->getFileName();
        }

        // .php
        $this->assertArrayNotHasKey(AFirstClass::class, $classes);
        $this->assertArrayNotHasKey(ASubFolderClass::class, $classes);
        $this->assertArrayNotHasKey(ASubSubFolderClass::class, $classes);
        $this->assertNotContains(__DIR__.'/Assets/AFirstClass.php', $classes);
        $this->assertNotContains(__DIR__.'/Assets/SubFolder/ASubFolderClass.php', $classes);
        $this->assertNotContains(__DIR__.'/Assets/SubFolder/ASubSubFolder/ASubSubFolderClass.php', $classes);

        // .inc
        $this->assertArrayHasKey(ADotIncClass::class, $classes);
        $this->assertArrayHasKey(ASubDotIncClass::class, $classes);
        $this->assertContains(__DIR__.'/Assets/ADotIncClass.inc', $classes);
        $this->assertContains(__DIR__.'/Assets/SubFolder/ASubDotIncClass.inc', $classes);
    }

    public function testMultiExtensions():void
    {
        $iterator = new RecursiveDirectoryClassesIterator(self::ASSETS_PATH, ['inc', 'php']);
        $this->assertInstanceOf(\Traversable::class, $iterator);
        $classes = [];
        foreach ($iterator as $class) {
            $this->assertTrue(
                substr($class->getFileName(), 0, strlen(self::ASSETS_PATH)) == self::ASSETS_PATH
            );
            $this->assertFileExists($class->getFileName());
            $classes[$class->getName()] = $class->getFileName();
        }
        $this->assertArrayHasKey(AFirstClass::class, $classes);
        $this->assertArrayHasKey(ADotIncClass::class, $classes);
        $this->assertArrayHasKey(AFirstClass::class, $classes);
        $this->assertArrayHasKey(ASubFolderClass::class, $classes);
        $this->assertArrayHasKey(ASubDotIncClass::class, $classes);
        $this->assertArrayHasKey(ASubSubFolderClass::class, $classes);
        $this->assertContains(__DIR__.'/Assets/AFirstClass.php', $classes);
        $this->assertContains(__DIR__.'/Assets/ADotIncClass.inc', $classes);
        $this->assertContains(__DIR__.'/Assets/AFirstClass.php', $classes);
        $this->assertContains(__DIR__.'/Assets/SubFolder/ASubFolderClass.php', $classes);
        $this->assertContains(__DIR__.'/Assets/SubFolder/ASubDotIncClass.inc', $classes);
        $this->assertContains(__DIR__.'/Assets/SubFolder/ASubSubFolder/ASubSubFolderClass.php', $classes);
    }
}