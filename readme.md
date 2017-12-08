## vue

> 应用开发流程:
0. feature->develop 合并分之到develop
1. develop->release 新建release
2. release->master 简单测试后，合并到master发布
3. release->develop 合并到develop

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

### 注意：master, develop 不能直接在上面开发
### 注意：feature用于开发功能, hotfix用于修复master bug

```
registry.npmjs.org/:_authToken=f5b13e5c-1240-4993-9945-1a354cb34bb8
electron_mirror=https://npm.taobao.org/mirrors/electron/
strict-ssl=false
registry = https://registry.npm.taobao.org
registry=http://registry.npmjs.org/
```