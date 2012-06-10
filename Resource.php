<?php
class Resource {

  /** The ID of this resource. */
  public $id = null;
  public $db = null;

  // Construct the resource.
  function __construct($params = null) {
    $this->db = new MongoResource('resources', $params);
    $this->set(array_merge($params, $this->mongoLoad()));
  }

  /** Generates a new uuid. */
  private function generate_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),
      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),
      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,
      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,
      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
  }

  /**
   * Handles a request.
   * @param type $request
   * @return type
   */
  public function handleRequest($request) {
    switch ($request->method) {
      case 'get':
        return $this->load();
        break;
      case 'put':
      case 'post':
        return $this->save();
        break;
      case 'delete':
        return $this->delete();
        break;
    }

    // Return an error.
    return new Response(406);
  }

  /** Get the values of this resource. */
  public function get() {
    return array(
      'id' => $this->id
    );
  }

  /** Set values within this object. */
  public function set($params) {
    $this->id = !empty($params['id']) ? $params['id'] : $this->generate_uuid();
  }

  /** Load values from the database */
  public function load() {
    return new Response(200, array_merge(
      $this->mongoLoad(),
      $this->get()
    ));
  }

  /** Save this resource to the database. */
  public function save() {
    if (($object = $this->get()) && $this->db->save($object)) {
      return new Response(200, $object);
    }
    return new Response(406, 'An error occured while saving.');
  }

  /** Deletes a resource from the database. */
  public function delete() {
    return $this->db->delete() ? new Response(200) : new Response(406);
  }

  /** Loads a mongo object, and sanitizes it. */
  private function mongoLoad() {
    $object = $this->db->load();
    unset($object['_id']);
    return $object ? $object : array();
  }
}
