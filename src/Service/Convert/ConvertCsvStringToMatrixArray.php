<?php

declare(strict_types=1);

namespace App\Service\Convert;

use function strlen;

// TODO 2024-07-01 HOW-TO: Wie würde man eine Klasse wie diese (csv-text to assoc array) am besten aufbauen?

class ConvertCsvStringToMatrixArray
{
    /** @var array<int, array<int, string>> */
    private array $lines;
    /** @var string[] */
    private array $columnCollector;

    private string $columnSeparator;
    private string $escapeMarker;
    private string $linebreak;
    private string $csvtext;
    private int $pointer;
    private string $error;
    private ?string $innerSeparatorReplacer;
    private int $lenColumnSeparator;
    private int $lenLinebreak;
    private int $lenEscapeMarker;
    private string $escapeInline;

    public function __construct(string $escapeMarker = '"', string $linebreak = "\r\n", ?string $innerSeparatorReplacer = '&nbsp;')
    {
        $this->escapeMarker = $escapeMarker;
        $this->linebreak = $linebreak;
        $this->innerSeparatorReplacer = $innerSeparatorReplacer;
    }

    /**
     * @return array<int, array<int, string>>|null
     */
    public function toMatrix(string $csvtext, string $columnSeparator = "\t"): ?array
    {
        $this->columnSeparator = $columnSeparator;
        $this->reset($csvtext);
        while ($this->pointer < strlen($this->csvtext) && $this->error === '') {
            $this->parsing();
        }
        if ($this->error !== '') {
            return null;
        }
        if (!empty($this->columnCollector)) {
            $this->lines[] = $this->columnCollector;
        }

        return $this->lines;
    }

    private function parsing(): void
    {
        if ($this->pointingToLinebreak()) {
            $this->nextLine();
        } elseif ($this->pointingToEscaper()) {
            $this->grabEscapedColumn();
        } elseif ($this->pointingToSeparator()) {
            $this->grabEmptyColumn();
        } else {
            $this->grabColumn();
        }
    }

    private function pointingToSeparator(): bool
    {
        return $this->columnSeparator === substr($this->csvtext, $this->pointer, $this->lenColumnSeparator);
    }

    private function pointingToEscaper(): bool
    {
        return $this->escapeMarker === substr($this->csvtext, $this->pointer, $this->lenEscapeMarker);
    }

    private function pointingToLinebreak(): bool
    {
        return $this->linebreak === substr($this->csvtext, $this->pointer, $this->lenLinebreak);
    }

    private function nextLine(): void
    {
        $this->lines[] = $this->columnCollector;
        $this->columnCollector = [];
        $this->pointer += $this->lenLinebreak;
    }

    private function grabEscapedColumn(): void
    {
        $column = substr($this->csvTextFromPointerToNextSeparator($this->escapeMarker . $this->columnSeparator), $this->lenEscapeMarker);
        $countEscapers = substr_count($column, $this->escapeMarker);
        $loopCount = 0;
        $pointer = $this->pointer;
        while ($loopCount < 1000 && $countEscapers % 2 !== 0) {
            $pointer = $this->nextSeparatorPosByPointer($this->escapeMarker . $this->columnSeparator, $pointer) + $this->lenEscapeMarker + $this->lenColumnSeparator;
            $column = substr($this->csvTextToNextSeparator($pointer, $this->escapeMarker . $this->columnSeparator), $this->lenEscapeMarker);
            $countEscapers = substr_count($column, $this->escapeMarker);
            ++$loopCount;
        }
        if ($countEscapers % 2 === 0) {
            $this->columnCollector[] = $this->fixInner($column);
            $this->pointer = $this->nextSeparatorPosByPointer($this->escapeMarker . $this->columnSeparator, $pointer) + $this->lenEscapeMarker + $this->lenColumnSeparator;
        } else {
            $this->error = 'error while retrieving column value';
        }
    }

    private function fixInner(string $text): string
    {
        if ($this->innerSeparatorReplacer !== null) {
            $text = str_replace($this->columnSeparator, $this->innerSeparatorReplacer, $text);
        }

        return str_replace($this->escapeInline, $this->escapeMarker, $text);
    }

    private function grabColumn(): void
    {
        $posSeparator = $this->nextSeparatorPos($this->columnSeparator);
        $posLinebreak = $this->nextSeparatorPos($this->linebreak);
        $separator = ($posSeparator < $posLinebreak) ? $this->columnSeparator : $this->linebreak;
        $column = $this->csvTextFromPointerToNextSeparator($separator);
        $this->columnCollector[] = $column;
        $this->pointer = $this->nextSeparatorPos($separator);
        if ($posSeparator < $posLinebreak) {
            $this->pointer += strlen($separator);
        }
    }

    private function grabEmptyColumn(): void
    {
        $this->columnCollector[] = '';
        $this->pointer += $this->lenColumnSeparator;
    }

    private function csvTextFromPointerToNextSeparator(string $separator): string
    {
        return substr($this->csvtext, $this->pointer, $this->nextSeparatorPos($separator) - $this->pointer);
    }

    private function csvTextToNextSeparator(int $pointer, string $separator): string
    {
        return substr($this->csvtext, $this->pointer, $this->nextSeparatorPosByPointer($separator, $pointer) - $this->pointer);
    }

    private function nextSeparatorPosByPointer(string $separator, int $pointer): int
    {
        $posOfSeparator = strpos($this->csvtext, $separator, $pointer + 1);

        return (false === $posOfSeparator) ? strlen($this->csvtext) : $posOfSeparator;
    }

    private function nextSeparatorPos(string $separator): int
    {
        $posOfSeparator = strpos($this->csvtext, $separator, $this->pointer + 1);

        return (false === $posOfSeparator) ? strlen($this->csvtext) : $posOfSeparator;
    }

    private function reset(string $csvtext): void
    {
        $this->csvtext = $csvtext;
        $this->lines = [];
        $this->columnCollector = [];
        $this->pointer = 0;
        $this->error = '';
        $this->lenLinebreak = strlen($this->linebreak);
        $this->lenColumnSeparator = strlen($this->columnSeparator);
        $this->lenEscapeMarker = strlen($this->escapeMarker);
        $this->escapeInline = $this->escapeMarker . $this->escapeMarker;
    }

    public function getError(): string
    {
        return $this->error;
    }
}
