<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Receipt</title>
	<!-- style pindah ke php -->
	<?php
		$style = '
			<style>
				* {
					font-family: "consolas", sans-serif;
				}
				p {
					display: block;
					margin: 3px;
					font-size: 10pt;
				}
				table td {
					font-size: 9pt;
				}
				.text-center {
					text-align: center;
				}
				.text-right {
					text-align: right;
				}
				@media print {
				@page {
					margin: 0;
					size: 75mm
		';
	?>
	<!-- size diatur menggunakan cookie -->
	<?php 
	    $style .= 
	        ! empty($_COOKIE['innerHeight'])
	        	? $_COOKIE['innerHeight'] .'mm; }'
	        	: '}';
	?>
	<?php
	    $style .= '
		            html, body {
		                width: 70mm;
		            }
		            .btn-print {
		                display: none;
		            }
		        }
		    </style>
		';
	?>

	{!! $style !!}
</head>
<body onload="window.print()">
	<button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
	<div class="text-center">
		<h3 style="margin-bottom: 5px;">PHARMACY</h3>
	</div>
	<br>
	<div>
		<p style="float: left;">{{ date('d-m-Y') }}</p>
		<p style="float: right;">{{ strtoupper(auth()->user()->name) }}</p>
	</div>
	<div class="clear-both" style="clear: both;"></div>
	<p>No: {{ tambah_nol_didepan($sales->sale_id, 9) }}</p>
	<p class="text-center">===================================</p>

	<br>
	<table width="100%" style="border: 0;">
		@foreach($detail as $item)
		<tr>
			<td colspan="3">{{ $item->items->name }}</td>
		</tr>
		<tr>
			<td>{{ $item->qty }} x {{ format_uang($item->sell_price) }}</td>
			<td></td>
			<td class="text-right">{{ format_uang($item->qty * $item->sell_price) }}</td>
		</tr>
		@endforeach
	</table>
	<p class="text-center">-----------------------------------</p>

	<table width="100%" style="border: 0;">
		<tr>
			<td>Total Price</td>
			<td class="text-right">{{ format_uang($sales->total_price) }}</td>
		</tr>
		<tr>
			<td>Total Item</td>
			<td class="text-right">{{ format_uang($sales->total_item) }}</td>
		</tr>
		<tr>
			<td>Diskon:</td>
			<td class="text-right">{{ format_uang($sales->diskon) }} %</td>
		</tr>
		<tr>
			<td>Payment</td>
			<td class="text-right">{{ format_uang($sales->payment) }}</td>
		</tr>
		<tr>
			<td>Receive</td>
			<td class="text-right">{{ format_uang($sales->receive) }}</td>
		</tr>
		<tr>
			<td>Change</td>
			<td class="text-right">{{ format_uang($sales->receive - $sales->payment) }}</td>
		</tr>
	</table>

	<p class="text-center">===================================</p>
	<p class="text-center">-- Thank You --</p>
<!-- script buat update cookie -->
	<script>
	let body = document.body;
	let html = document.documentElement;
	let height = Math.max(
	        body.scrollHeight, body.offsetHeight,
	        html.clientHeight, html.scrollHeight, html.offsetHeight
	    );

	document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
	document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
	</script>
</body>
</html>