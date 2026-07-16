<?php

namespace MKH\TeacherAddon\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Hook_Loader {
	/**
	 * @var array<int, array<string, mixed>>
	 */
	private array $actions = array();

	/**
	 * @var array<int, array<string, mixed>>
	 */
	private array $filters = array();

	public function add_action( string $hook, $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->actions[] = array(
			'hook'           => $hook,
			'callback'       => $callback,
			'priority'       => $priority,
			'accepted_args'  => $accepted_args,
		);
	}

	public function add_filter( string $hook, $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->filters[] = array(
			'hook'           => $hook,
			'callback'       => $callback,
			'priority'       => $priority,
			'accepted_args'  => $accepted_args,
		);
	}

	public function run(): void {
		foreach ( $this->actions as $action ) {
			add_action( $action['hook'], $action['callback'], $action['priority'], $action['accepted_args'] );
		}

		foreach ( $this->filters as $filter ) {
			add_filter( $filter['hook'], $filter['callback'], $filter['priority'], $filter['accepted_args'] );
		}
	}
}

