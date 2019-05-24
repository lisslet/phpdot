window.addEventListener('load', () => {
	const bolds = document.getElementsByTagName('b');
	if (bolds) {
		Array.from(bolds).forEach(bold => {
			if (bold.innerHTML.endsWith('.php')) {
				const context = bold.previousSibling;
				const type    = context.previousSibling;
				if (type.tagName && type.tagName === 'B') {
					const node     = document.createElement('div');
					node.className = 'phpdot-php-error';
					node.innerHTML = context.nodeValue.replace(/(Class|method) ([^\s]+)/g, $subject)
						.replace(/Stack trace:((?:.|\n)+)thrown/g, $stacks)
						.replace(/methods \(([^)]+)\)/g, $items);
					context.parentNode.insertBefore(node, bold);
					context.parentNode.removeChild(context);
				}
			}
		});
	}

	function $consolas(value) {
		return '<code style="background-color:#f0f0f0;font-family:consolas, sans-serif">' + value + '</code>';
	}

	function $glue(value) {
		return '<span style="color:#999;padding:0 0.3125em">' + value + '</span>';
	}

	function $subject(all, type, name) {
		return type + ' ' + $class(name);
	}

	function $list(type, items) {
		return '<' + type + '><li>' + items.join('</li><li>') + '</li></' + type + '>';
	}

	function $ul(items) {
		return $list('ul', items);
	}

	function $ol(items) {
		return $list('ol', items);
	}


	function $class(value) {
		value    = value.split('\\');
		let last = value.pop();
		last     = last.replace(/(::|-&gt;)([^(]+)(.+)?/, $method);
		last     = last.bold();
		value.push(last);

		return $consolas(value.join($glue('\\')));
	}


	function $method(all, glue, method, args) {
		return [
			$glue(glue),
			'<span style="background-color:#ffc">' + method + '</span>',
			args ? '<span style="background-color:#ffc">' + args + '</span>' : ''
		].join('');
	}

	function $stacks(all, stack) {
		stack = stack.split(/#\d+/).reverse();
		stack.pop();
		stack = $ol($traces(stack));
		return stack;
	}

	function $traces(items) {
		let i       = items.length;
		const regex = /([^(]+)\((\d+)\):(.+)/;
		while (i-- > 0) {
			items[i] = items[i].replace(regex, $trace);
		}
		return items;
	}

	function $trace(all, file, line, call) {
		return file + ' on line ' + line + '<br>' + $class(call);
	}

	function $items(all, methods) {
		const items = methods.split(',');
		let i       = items.length;
		while (i-- > 0) {
			items[i] = $class(items[i]);
		}
		return $ul(items);
	}
});