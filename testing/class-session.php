<?

// Test class, playing with object-oriented sessions
class Session {

    public $session_id;
    public $session_name = "Unnamed Session";
    public $session_created;
    public $session_update;
    public $session_time;

    public $session_password = null;

    public $session_locked = false;
    public $session_desc = "No description provided.";

    public $session_log = "No log details added yet.";

    public $session_reward = null;

    public $session_complete = false;

    public function __construct($session_array) {
        $this->session_id = $session_array['session_id'];
        $this->session_name = $session_array['session_name'];
        $this->session_created = $session_array['session_created'];
        $this->session_update = $session_array['session_update'];
        $this->session_time = $session_array['session_time'];
        if ($session_array['session_password'] != null) $this->session_password = $session_array['session_password'];
        if ($session_array['session_locked'] == 1) $this->session_locked = $session_array['session_locked'];
        if ($session_array['session_desc'] != null) $this->session_desc = $session_array['session_desc'];
        if ($session_array['session_log'] != null) $this->session_log = $session_array['session_log'];
        if ($session_array['session_reward'] != null) $this->session_reward = $session_array['session_reward'];
        if ($session_array['session_complete'] == 1) $this->session_complete = $session_array['session_complete'];
        
        echo sprintf('Session ID# %s "%s" was constructed.', $this->session_id, $this->session_name);
    }
 
    /*public function __destruct() {
        echo 'Memory reference to ' . __CLASS__ . ' was destroyed.<br />';
    }*/
 
    public function __toString() {
        echo "Using the toString method: ";
    }    

}

?>