set title "Gmail Quota"
#set style data fsteps
set xlabel "Date Time (UTC)"
set timefmt "%s"
set xdata time
set ylabel "Quota (MB)"
set format x "%y-%m-%d\n%H:%M:%S"
set grid
unset key
plot 'data.dat' using 1:2 with lines, \
     'data.dat' using 1:2 with impulses, \
     'data.dat' using 1:2 with points
