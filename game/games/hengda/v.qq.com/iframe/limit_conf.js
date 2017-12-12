var g_limit_conf={
"1": {
"promotionId": 755,
"downloadUrl": "http://mcgi.v.qq.com/commdatav2?cmd=4&confid=755&platform=aphone",
"texts": {
"download": "下载腾讯视频，看精彩完整版",
"downloading": "正在下载腾讯视频，马上就能看哟",
"pause": "已暂停，点击恢复下载",
"install": "下载已完成，点击安装",
"afterInstall": "安装已完成，请打开腾讯视频看完整版",
"afterInstall_1":"打开腾讯视频，看精彩完整版",
"_downloading": "正在下载",
"_pause": "点击恢复",
"_install": "立即安装",
"_afterInstall": "腾讯视频看更多",
"_afterInstall_1":"腾讯视频看更多",
"playText": '试看${limitTime}分钟',
"replayText": '重新播放',
"timingText": '腾讯视频看更多'
}
},
"2": {
"promotionId": 756,
"downloadUrl": "http://mcgi.v.qq.com/commdatav2?cmd=4&confid=756&platform=aphone",
"texts":{
"download": "下载腾讯视频，看精彩完整版",
"playText": '试看${limitTime}分钟',
"replayText": '重新播放',
"timingText": '腾讯视频看更多'
}
},
"3": {
"promotionId": 754,
"downloadUrl": "http://mcgi.v.qq.com/commdatav2?cmd=4&confid=754&platform=aphone",
"texts": {
"download": "腾讯视频看更多",
"downloading": "正在下载",
"pause": "点击恢复",
"install": "立即安装",
"afterInstall": "腾讯视频看更多",
"timingText": '腾讯视频看更多'
},
"per":20,
"disable": [2,3,4,5]
},
case_regx: [
/MicroMessenger/i,
/QQ\/(\d+\.(\d+)\.(\d+)\.(\d+))/i,
/qqnews\/(\d+\.\d+\.\d+)/
]
};
