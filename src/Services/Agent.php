<?php namespace VnSource\Services;

use Jenssegers\Agent\Agent as AgentDetect;

class Agent
{
    protected $app;
    protected $agent;

    public function __construct($app) {
        $this->app = $app;
        $this->agent = new AgentDetect();
    }

    public function get()
    {
        return $this->agent;
    }
}