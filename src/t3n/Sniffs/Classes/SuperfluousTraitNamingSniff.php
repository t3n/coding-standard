<?php

declare(strict_types=1);

namespace t3n\CodeStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use const T_TRAIT;
use function sprintf;
use function strtolower;
use function substr;

class SuperfluousTraitNamingSniff implements Sniff
{

    public const CODE_SUPERFLUOUS_PREFIX = 'SuperfluousPrefix';
    public const CODE_SUPERFLUOUS_SUFFIX = 'SuperfluousSuffix';

    /**
     * @return mixed[]
     */
    public function register(): array
    {
        return [ T_TRAIT ];
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     * @param int $traitPointer
     */
    public function process(File $phpcsFile, $traitPointer): void
    {
        $traitName = ClassHelper::getName($phpcsFile, $traitPointer);

        $this->checkPrefix($phpcsFile, $traitPointer, $traitName);
        $this->checkSuffix($phpcsFile, $traitPointer, $traitName);
    }

    private function checkPrefix(File $phpcsFile, int $traitPointer, string $traitName): void
    {
        $prefix = substr($traitName, 0, 9);

        if (strtolower($prefix) !== 'trait') {
            return;
        }

        $phpcsFile->addError(sprintf('Superfluous prefix "%s".', $prefix), $traitPointer, self::CODE_SUPERFLUOUS_PREFIX);
    }

    private function checkSuffix(File $phpcsFile, int $traitPointer, string $traitName): void
    {
        $suffix = substr($traitName, -5);

        if (strtolower($suffix) === 'trait') {
            return;
        }

        $phpcsFile->addError('Missing suffix "Trait".', $traitPointer, self::CODE_SUPERFLUOUS_SUFFIX);
    }

}
