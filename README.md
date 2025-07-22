3D Bin Packing
====

3D Bin Packing PHP implementation based on [this python library](https://github.com/enzoruiz/3dbinpacking/). The code is based on [gedex](https://github.com/gedex/bp3d) implementation in Go.



## Install

```
just put php3dbp.php where you need
```

## Basic Explanation

Bin and Items have the same creation params:
```
$my_bin = new Bin($name, $width, $height, $depth, $max_weight);
$my_item = new Item($name, $width, $height, $depth, $weight);
```
Packer have three main functions:
```
packer = new Packer();           # PACKER DEFINITION

packer->add_bin($my_bin);      # ADDING BINS TO PACKER
packer->add_item(my_item);    # ADDING ITEMS TO PACKER

packer->pack();               
```

After packing:
```
packer->bins                 # GET ALL BINS OF PACKER
my_bin->items                # GET ALL FITTED ITEMS IN EACH BIN
my_bin>unfitted_items       # GET ALL UNFITTED ITEMS IN EACH BIN
```


## Usage

```
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

```


## Versioning
- **0.x**
    - First Fuctional release.

## Credit

* https://github.com/enzoruiz/3dbinpacking/

## License

[MIT](./LICENSE)


