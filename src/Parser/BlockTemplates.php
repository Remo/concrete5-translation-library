<?php
namespace C5TL\Parser;

/**
 * Extract translatable strings from block type templates
 */
class BlockTemplates extends \C5TL\Parser
{
    /**
     * @see \C5TL\Parser::getParserName()
     */
    public function getParserName()
    {
        return 'Block templates';
    }

    /**
     * @see \C5TL\Parser::canParseDirectory()
     */
    public function canParseDirectory()
    {
        return true;
    }

    /**
     * @see \C5TL\Parser::parseDirectoryDo()
     */
    protected function parseDirectoryDo(\Gettext\Translations $translations, $rootDirectory, $relativePath)
    {
        $templateHandles = array();
        $prefix = ($relativePath === '') ? '' : "$relativePath/";
        foreach ($this->getDirectoryStructure($rootDirectory) as $child) {
            $shownChild = $prefix . $child;
            if (preg_match('%(?:^|/)blocks/\w+/templates/(\w+)$%', $shownChild, $matches)) {
                if (!isset($templateHandles[$matches[1]])) {
                    $templateHandles[$matches[1]] = array();
                }
                $templateHandles[$matches[1]][] = $shownChild;
            } elseif (preg_match('%(^|/)blocks/\w+/templates$%', $shownChild)) {
                $fullpath = "$rootDirectory/$child";
                $contents = @scandir($fullpath);
                if ($contents === false) {
                    throw new \Exception("Unable to parse directory $fullpath");
                }
                foreach ($contents as $file) {
                    if ($file[0] !== '.') {
                        if (preg_match('/^(.*)\.php$/', $file, $matches) && is_file("$fullpath/$file")) {
                            if (!isset($templateHandles[$matches[1]])) {
                                $templateHandles[$matches[1]] = array();
                            }
                            $templateHandles[$matches[1]][] = $shownChild . "/$file";
                        }
                    }
                }
            }
        }
        foreach ($templateHandles as $templateHandle => $references) {
            $translation = $translations->insert('TemplateFileName', static::unhandleString($templateHandle));
            foreach ($references as $reference) {
                $translation->addReference($reference);
            }
        }
    }
}
