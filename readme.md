## vue
> 应用开发流程
1. develop->release 新建release
2. release->master 

> bug修复流程： 
1. master->hotfix 新建分之
2. hotfix->master 合并分之到master
3. hotfix->develop 合并分之到develop
4. 删除hotfix分之

- master分支（1个）， 用于发布
- develop分支（1个），开发新的功能, dev不能直接到master, 
- feature分支。同时存在多个。
- release分支。同一时间只有1个，生命周期很短，只是为了发布。
- hotfix分支。同一时间只有1个。生命周期较短，用了修复bug或小粒度修改发布。

在这个模型中，master和develop都具有象征意义。master分支上的代码总是稳定的（stable build），随时可以发布出去。develop上的代码总是从feature上合并过来的，可以进行Nightly Builds，但不直接在develop上进行开发。当develop上的feature足够多以至于可以进行新版本的发布时，可以创建release分支。

release分支基于develop，进行很简单的修改后就被合并到master，并打上tag，表示可以发布了。紧接着release将被合并到develop；此时develop可能往前跑了一段，出现合并冲突，需要手工解决冲突后再次合并。这步完成后就删除release分支。

当从已发布版本中发现bug要修复时，就应用到hotfix分支了。hotfix基于master分支，完成bug修复或紧急修改后，要merge回master，打上一个新的tag，并merge回develop，删除hotfix分支。

由此可见release和hotfix的生命周期都较短，master/develop虽然总是存在但却不常使用。