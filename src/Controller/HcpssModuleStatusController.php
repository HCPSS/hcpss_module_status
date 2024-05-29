<?php

declare(strict_types=1);

namespace Drupal\hcpss_module_status\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\update\UpdateManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns responses for HCPSS Module Status routes.
 */
final class HcpssModuleStatusController extends ControllerBase {

  /**
   * The controller constructor.
   */
  public function __construct(
    private readonly UpdateManagerInterface $updateManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('update.manager'),
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): Response {
    if ($available = update_get_available(TRUE)) {
      $this->moduleHandler()->loadInclude('update', 'compare.inc');
    }

    $calculated_updates = update_calculate_project_data($available);

    return new JsonResponse(array_values($calculated_updates));
  }
}
