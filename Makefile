
run_all_in_parallel:
	make -j chrome internetExplorer firefox firefoxOSX

chrome:
	./bin/behat -p chrome

internetExplorer:
	./bin/behat -p internetExplorer

firefox:
	./bin/behat -p firefox

firefoxOSX:
	./bin/behat -p firefoxOSX

