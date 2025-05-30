<?php

namespace App\Helpers;

class ChartHelper
{
  public static function prepareChartConfig(
    string $title,
    string $type,
    array $rawData,
    array $labels = [],
    array $colors = []
  ): array {
    $isDoughnut = $type === 'doughnut' || $type === 'pie';

    if ($isDoughnut) {
      return [
        'type' => $type,
        'data' => [
          'labels' => array_keys($rawData),
          'datasets' => [
            [
              'label' => $title,
              'data' => array_values($rawData),
              'backgroundColor' =>
                array_values($colors) ?: self::generateColors(count($rawData)),
              'borderWidth' => 1,
            ],
          ],
        ],
        'options' => [
          'responsive' => true,
          'plugins' => [
            'title' => [
              'display' => true,
              'text' => $title,
            ],
          ],
        ],
      ];
    }

    // For line/multi-series
    $dates = collect($rawData)
      ->flatMap(fn($s) => array_keys($s))
      ->unique()
      ->sort()
      ->values()
      ->all();

    $datasets = [];
    foreach ($rawData as $key => $series) {
      $data = [];
      foreach ($dates as $date) {
        $data[] = $series[$date] ?? 0;
      }

      if (!$colors) {
        $colors = self::getColors();
      }

      $datasets[] = [
        'label' => $labels[$key] ?? $key,
        'data' => $data,
        'fill' => false,
        'borderColor' => $colors[$key] ?? 'gray',
        'tension' => 0.3,
      ];
    }

    return [
      'type' => $type,
      'data' => [
        'labels' => $dates,
        'datasets' => $datasets,
      ],
      'options' => [
        'responsive' => true,
        'plugins' => [
          'title' => [
            'display' => true,
            'text' => $title,
          ],
        ],
        'scales' => [
          'y' => ['beginAtZero' => true],
        ],
      ],
    ];
  }

  public static function generateColors(int $count): array
  {
    $colors = [
      'rgba(255, 99, 132, 0.6)',
      'rgba(54, 162, 235, 0.6)',
      'rgba(255, 206, 86, 0.6)',
      'rgba(75, 192, 192, 0.6)',
      'rgba(153, 102, 255, 0.6)',
      'rgba(255, 159, 64, 0.6)',
      'rgba(201, 203, 207, 0.6)',
    ];

    return array_pad($colors, $count, 'rgba(100, 100, 100, 0.6)');
  }

  public static function getColors(): array
  {
    return [
      0 => 'rgba(255, 99, 132, 1)',
      1 => 'rgba(255, 206, 86, 1)',
      2 => 'rgba(54, 162, 235, 1)',
      3 => 'rgba(75, 192, 192, 1)',
      4 => 'rgba(100, 100, 100, 0.6)',
    ];
  }
}
