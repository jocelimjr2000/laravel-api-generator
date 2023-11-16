<?php

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;

class CreateUsesUUIDTraitWriter extends AbstractWriter
{

    /**
     * @param ObjGenDTO $objGenDTO
     */
    public function __construct(ObjGenDTO $objGenDTO)
    {
        parent::__construct($objGenDTO);
    }

    public function write(): void
    {
        $usesUUIDTraitStub = FileHelper::getStubPath('uuid.trait');

        // Write
        $this->writeFile(['app', 'Traits', 'UsesUuid.php'], $usesUUIDTraitStub);
    }

}
