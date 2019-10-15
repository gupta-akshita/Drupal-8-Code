<?php

namespace Drupal\rsvplist;

use Drupal\Core\Database\Connection;
use Drupal\Node\Entity\Node;

/**
 * Defines a service for managing RSVP list enabled for nodes.
 */
class EnablerService {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  private $connection;

  /**
   * Constructor.
   *
   * @param Connection $connection
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * Sets RSVP for an individual node.
   *
   * @param \Drupal\Node\Entity\Node $node
   *   The node object to enable rsvp lists on.
   *
   * @throws \Exception
   */
  public function setEnabled(Node $node) {
    if (!$this->isEnabled($node)) {
      $insert = $this->connection->insert('rsvplist_enabled');
      $insert->fields(['nid'], [$node->id()]);
      $insert->execute();
    }
  }

  /**
   * Checks if an individual node is RSVP Enabled.
   *
   * @param \Drupal\Node\Entity\Node $node
   *   The node object to check.
   *
   * @return bool
   *   Returns True or False for a given node.
   */
  public function isEnabled(Node $node) {
    if ($node->isNew()) {
      return FALSE;
    }
    $select = $this->connection->select('rsvplist_enabled', 're');
    $select->fields('re', ['nid']);
    $select->condition('nid', $node->id());
    $results = $select->execute();
    return !empty($results->fetchCol());
  }

  /**
   * Deletes enabled settings for an individual node.
   *
   * @param \Drupal\Node\Entity\Node $node
   *   The node object to delete collections for.
   */
  public function delEnabled(Node $node) {
    $delete = $this->connection->delete('rsvplist_enabled');
    $delete->condition('nid', $node->id());
    $delete->execute();
  }

}
