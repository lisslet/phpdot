<?php

namespace Dot\Html;

class Pagebar
{
	protected $total;
	protected $page;
	protected $limit;

	function __construct(int $total, int $limit, int $page = 1)
	{
		$this->total = $total;
		$this->page = $page;
		$this->limit = $limit;
	}

	function __toString()
	{
		return $this->toUl();
	}

	function href(string $href)
	{
		$this->href = preg_replace('#page=\d+&?#', '', $href);

		return $this;
	}

	function toUl(): string
	{
		$total = $this->total;
		$limit = $this->limit;
		$totalPage = $total / $limit;
		$href = $this->href;

		$now = $this->page;

		$html = [];
		$html[] = '<ul class="pagebar">';
		for ($page = 1, $end = $totalPage + 1; $page < $end; $page++) {
			$classList = ['pagebar-link'];
			if ($page == $now) {
				$classList[] = 'pagebar-link_active';
			}
			$classList = \implode(' ', $classList);
			$html[] = '<li class="' . $classList . '">';
			$html[] = '<a href="' . $href . '&page=' . $page . '">';
			$html[] = $page;
			$html[] = '</a>';
			$html[] = '</li>';
		}
		$html[] = '</ul>';
		$html = \implode(\PHP_EOL, $html);

		return $html;
	}
}