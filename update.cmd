@echo off
color 00
title .
hg pull
title ..
hg up
title ...
php yii migrate
title ....
php yii updater
title .....
exit