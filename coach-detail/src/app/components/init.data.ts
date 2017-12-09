import * as queryString from 'query-string';
const parsed = queryString.parse(location.search);

export const defaultCoach = {
    avatar: 'http://meepo.com.cn/addons/imeepos_runnerpro/icon.jpg',
    title: '同城预约',
    desc: '同城预约专注即时同城快递，提供10分钟上门59分钟送达全城的同城快递及跑腿服务。同城快递找小明跑腿，7*24提供安全、高效、便捷的高端服务！小明跑腿，让生活更高效！',
    fee: 10,
    timeLen: 30,
    id: parsed.id ? parsed.id : 1,
    itmes: [],
    stars: [{
        avatar: 'https://images.daojia.com/dop/custom/12601bba1ccb4b4af531e3a9ed5265ee.png@300w_300h_1wh.jpg',
        nickname: '咚咚',
        jingh: true,
        content: '很好',
        create_time: '2017.11.09',
        title: '洗衣做饭'
    }]
};

export const defaultWidget = {
    content: '58同城杭州家装频道免费提供给您大量真实有效的杭州家装服务,杭州装修公司,杭州装修队信息查询，同时您可以免费发布杭州家装服务,杭州家装公司,杭州装修队信息。专业的杭州家装服务信息就在58同城杭州家装服务频道。-58.com',
    lastDate: new Date,
    selected: [],
    max: 200,
    loading: false,
    action: 'pay',
    time: {
        start: {
            hour: 7,
            minute: 0
        },
        end: {
            hour: 22,
            minute: 0
        }
    },
    tabs: [
        {
            title: '预约',
            active: true,
            code: 'coach',
            role: 'member'
        },
        {
            title: '记录',
            active: false,
            num: 0,
            code: 'star',
            role: 'member'
        },
        {
            title: '名片',
            active: false,
            code: 'field',
            role: 'member'
        },
        {
            title: '设置',
            active: false,
            code: 'setting',
            role: 'owner'
        }
    ]
};

export const defaultForm = {
    desc: '',
    time: [],
    id: 1
};



