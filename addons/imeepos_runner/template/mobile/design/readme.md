### 

- init
- content 组件设置
- children 下级
- forms 表单数据
- actions 操作数据
- binds

```ts
{
    type: 'meepo-test',
    name: '测试组件'，
    content: {
        // 配置数据 结构对象
        title: '测试标题'
    },
    children: [
        // 列表数据 数组
    ],
    forms: {
        // 表单数据
        action: '提交地址',
        finish: ()=>{},
        error: ()=>{}
    }
}
```