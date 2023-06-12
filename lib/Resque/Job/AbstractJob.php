<?php

abstract class AbstractJob
{
    public Resque_Job $job;
    public string $queue;
    public array $args;

    public abstract function perform(): void;
}
