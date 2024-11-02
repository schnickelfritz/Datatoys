<?php

declare(strict_types=1);

namespace App\Service\Convert;

use function sprintf;
use function strlen;

// TODO 2024-07-01 SESSION-TOPIC: Wie würde man eine Klasse wie diese (csv-text to assoc array) am besten aufbauen?

class ConvertCsvStringToMatrixArray
{
    private string $columnSeparator;
    private string $escapeMarker;
    private string $linebreak;
    private string $csvtext;
    /** @var array<int, array<int, string>> */
    private array $lines;
    /** @var string[] */
    private array $columnCollector;
    private int $pointer;
    private string $error;

    public function __construct(string $escapeMarker = '"', string $linebreak = "\n")
    {
        $this->escapeMarker = $escapeMarker;
        $this->linebreak = $linebreak;
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
            $this->grabLine();
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
        return $this->columnSeparator === substr($this->csvtext, $this->pointer, strlen($this->columnSeparator));
    }

    private function pointingToEscaper(): bool
    {
        return $this->escapeMarker === substr($this->csvtext, $this->pointer, strlen($this->escapeMarker));
    }

    private function pointingToLinebreak(): bool
    {
        return $this->linebreak === substr($this->csvtext, $this->pointer, strlen($this->linebreak));
    }

    private function grabLine(): void
    {
        $this->lines[] = $this->columnCollector;
        $this->columnCollector = [];
        $this->pointer += strlen($this->linebreak);
    }

    private function grabEscapedColumn(): void
    {
        $column = substr($this->csvTextFromPointerToNextSeparator($this->escapeMarker . $this->columnSeparator), strlen($this->escapeMarker));
        $countEscapers = substr_count($column, $this->escapeMarker);
        if ($countEscapers % 2 === 0) {
            $this->columnCollector[] = $this->fixInnerEscapes($column);
            $this->pointer = $this->nextSeparatorPos($this->escapeMarker . $this->columnSeparator) + strlen($this->escapeMarker) + strlen($this->columnSeparator);
        } else {
            // TODO 2024-11-02 column
            $this->error = sprintf('fail grabbing escaped column because column content contains separator(s) (%s)', $column);
        }
    }

    private function fixInnerEscapes(string $text): string
    {
        return str_replace($this->escapeMarker . $this->escapeMarker, $this->escapeMarker, $text);
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
        $this->pointer += strlen($this->columnSeparator);
    }

    private function csvTextFromPointerToNextSeparator(string $separator): string
    {
        return substr($this->csvtext, $this->pointer, $this->nextSeparatorPos($separator) - $this->pointer);
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
    }

    public function getError(): string
    {
        return $this->error;
    }
}