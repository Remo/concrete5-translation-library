<?php
namespace C5TL\Parser\DynamicItem;

/**
 * Extract translatable data from PermissionAccessEntityTypes
 */
class PermissionAccessEntityType extends DynamicItem
{
    /**
     * @see \C5TL\Parser\DynamicItem::getClassNameForExtractor()
     */
    protected static function getClassNameForExtractor()
    {
        return '\Concrete\Core\Permission\Access\Entity\Type';
    }

    /**
     * @see \C5TL\Parser\DynamicItem::parseManual()
     */
    public static function parseManual(\Gettext\Translations $translations, $concrete5version)
    {
        if (class_exists('\PermissionAccessEntityType', true) && method_exists('\PermissionAccessEntityType', 'getList')) {
            foreach (\PermissionAccessEntityType::getList() as $aet) {
                self::addTranslation($translations, $aet->getAccessEntityTypeName(), 'PermissionAccessEntityTypeName');
            }
        }
    }
}
