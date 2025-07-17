<?php
require("php3dbp.php");
$packer = new Packer();

$packer->add_bin(new Bin('small-box', 14, 9, 6, 70.0));
$packer->add_bin(new Bin('medium-box', 30, 15, 15, 70.0));


$packer->add_item(new Item('1 [powder 3]', 3, 2, 2, 3));
$packer->add_item(new Item('2 [powder 3]', 3, 2, 2, 3));
$packer->add_item(new Item('3 [powder 3]', 3, 2, 2, 3));
$packer->add_item(new Item('4 [powder 3]', 3, 2, 2, 3));
$packer->add_item(new Item('10 [powder 7]', 7, 4, 4, 3));
$packer->add_item(new Item('20 [powder 7]', 7, 4, 4, 3));
$packer->add_item(new Item('30 [powder 7]', 7, 4, 4, 3));
$packer->add_item(new Item('40 [powder 7]', 7, 4, 4, 3));

$packer->pack();

for ($i = 0; $i<count($packer->bins); $i++){
	echo $packer->bins[$i]."\n";
	
	echo "fitted items"."\n";
	foreach($packer->bins[$i]->items as $item){
		echo "=====> ".$item."\n";
	}
	echo "unfitted items"."\n";
	foreach($packer->bins[$i]->unfitted_items as $item){
		echo "=====> ".$item."\n";
	}
}

?>
