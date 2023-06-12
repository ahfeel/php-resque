<?php

abstract class Resque_Job_AbstractJob
{
    public Resque_Job $job;
    public string $queue;
    public array $args;

    public abstract function perform();
}
