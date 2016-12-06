// ==================================================================== //
// BEHAVIOR

// Find all Neo blocks, add specified background color
function colorizeNeoBlocks() {
	var blockType;
	$('.neoblock').each(function () {
		blockType = $(this).find('input[type="hidden"][name*="][type]"]').val();
		// If block type is in the color list
		if (-1 < colorList.indexOf(blockType)) {
			$(this).addClass('mc-solid-'+blockType);
			$(this).find('.titlebar').css({'background-color':'rgba(255, 255, 255, 0.5)'});
		}
	});
}

// Find buttons related to Neo, update background color
function colorizeNeoButtons() {
	for (var i in colorList) {
		$('.neo').find('.btn[data-type="'+colorList[i]+'"]').addClass('mc-gradient-'+colorList[i]);
	}
}

// Find list items in menus related to Neo, update background color
function colorizeNeoMenus() {
	for (var i in colorList) {
		$('.menu').find('a[data-type="'+colorList[i]+'"]').addClass('mc-solid-'+colorList[i]);
	}
}

// Colorize all components
function colorizeAll() {
	colorizeNeoBlocks();
	colorizeNeoButtons();
	colorizeNeoMenus();
}

// Refresh colorization over a timed period
function timedRefresh() {
	var counter = 1;
	var maxLoops = 10;
	var loop = setInterval(function () {
		colorizeAll();
		if (maxLoops <= counter++) {
			clearInterval(loop);
		}
	}, 200);
}

// ==================================================================== //
// TRIGGERS

// On load, colorize blocks
$(function () {
	colorizeAll();
	// Colorize existing menus
	var observer = new MutationObserver(function() {
		colorizeNeoMenus();
	});
	observer.observe(document.body, {childList: true});
});

// Listen for new blocks
$(document).on('click', '.neo .btn, .menu ul li a', function () {
	colorizeNeoBlocks();
	colorizeNeoMenus();
});

// Listen for changed entry type
$(document).on('change', '#entryType', function () {
	timedRefresh();
});

// Listen for new Super Table rows
$(document).on('click', '.superTableContainer .btn', function () {
	timedRefresh();
});
