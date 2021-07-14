window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
//  try {
//     window.Popper = require('popper.js').default;
//     window.$ = window.jQuery = require('jquery');

//     require('bootstrap');
// } catch (e) {}

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true,
    // auth: {
    //     headers: {
    //         Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiNzkyMmNlMTY1MmRjNTk5NGUzNzNiM2I3ZjM3MTg3MjUwNWQ5M2QyOWU5NmJkOTM1NDViNTUxOWQ3MTExZjAzZmMxY2U5MGY4MzFlOWFhM2EiLCJpYXQiOjE2MTkxNDc3NjguNTEyMjExLCJuYmYiOjE2MTkxNDc3NjguNTEyMjIsImV4cCI6MTY1MDY4Mzc2OC40OTM0MDksInN1YiI6IjEiLCJzY29wZXMiOltdfQ.iq9FDH2GeLcSLwaOSs2IxkjthtI4AoV2-sX7Y4NsDjUesEty78MXIvT-oIFCOBZeC8BcwVhEWftttFSrXidpilURika4uiE4A6ztGQgKcg3mJ91gNrtQVCnw7465lKf94G4TtRm-ToklV_8ErsrsrACo1BF4KVrnJwyo2SLZSomu9AZFN8ekAUD2wWZX7LzqUBKqHpI1jwOv-Q8li2G4CNFB_gi2gHhfn57NwOSoR72fKYdxqgN2td4nnJinpBKEUy6t6Ky4w4LUiZt1-TD1WH9u9PedV3MebcAoguDmuhpD7MjFWp840w0goG5ro15rF8xkwJ9aQuv88oWlYRYoXCbavx6uR_YnYjgJIoimKHRSV9Qh8tnZilhri-pR9Ntd98qVxlwnkaOt8RKB1dIU_YtAv0JlmE6czYS-3THyeefN0eFaZA52J-vzbChKk4aOmkszbCTLi00YGTMlvovmOhfN_zhHd-Fd1LFPSPOIPy1d3RNY861vJt2erVP2ZzpZ5CrEhSjfqKHpQWIPA1xyPHsV3sMpe7Jr-mR2dzWL5l9Hvl2GaKzAiEwTThn6DPb83zCTfoI_mUw6nPzPlKSo_opmVcMbDTi48YscqiU5zMW3QIYRd3_GLE8dWaScR_4UKjm-lIsMxYdPuINPHQRJsAw274f4JDbCvG2UC_QQrBs '
    //     },
    // },
});

// Echo.private('test')
//     .listen('TestEvent', () => {
//         console.log('abcdef');
//     });
