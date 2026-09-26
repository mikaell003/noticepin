(function () {

    'use strict';

    const currentScript =
        document.currentScript;

    if (!currentScript) {
        return;
    }

    const siteId =
        currentScript.getAttribute('data-site');

    if (!siteId) {
        console.warn('NoticePin: Missing data-site.');
        return;
    }

    const scriptUrl =
        new URL(currentScript.src);

    const baseUrl =
        scriptUrl.origin;

    const apiUrl =
        baseUrl + '/api/notice.php?site=' +
        encodeURIComponent(siteId);

    function loadStyles() {

        if (document.getElementById('noticepin-styles')) {
            return;
        }

        const style =
            document.createElement('style');

        style.id = 'noticepin-styles';

        style.textContent = `

            #noticepin-widget {
                position: fixed;
                z-index: 2147483647;

                width: 300px;

                padding: 22px;

                box-sizing: border-box;

                border-radius: 4px;

                box-shadow:
                    0 12px 35px rgba(0,0,0,.18);

                font-family:
                    Arial,
                    Helvetica,
                    sans-serif;

                transform-origin: center;

                animation:
                    noticepin-enter .35s ease;

            }

            #noticepin-widget * {
                box-sizing: border-box;
            }

            #noticepin-widget
            .noticepin-pin {

                position: absolute;

                top: -13px;
                left: 50%;

                transform:
                    translateX(-50%)
                    rotate(-3deg);

                font-size: 25px;

                filter:
                    drop-shadow(
                        0 2px 2px
                        rgba(0,0,0,.2)
                    );

            }

            #noticepin-widget
            .noticepin-close {

                position: absolute;

                top: 8px;
                right: 10px;

                width: 25px;
                height: 25px;

                border: 0;

                background: transparent;

                font-size: 18px;

                cursor: pointer;

                opacity: .55;

            }

            #noticepin-widget
            .noticepin-title {

                margin: 4px 20px 10px 0;

                font-size: 19px;

                line-height: 1.25;

                font-weight: 800;

            }

            #noticepin-widget
            .noticepin-message {

                margin: 0 0 17px;

                font-size: 14px;

                line-height: 1.55;

            }

            #noticepin-widget
            .noticepin-button {

                display: inline-block;

                padding: 9px 14px;

                border-radius: 7px;

                background: rgba(0,0,0,.82);

                color: white;

                text-decoration: none;

                font-size: 13px;

                font-weight: 700;

                cursor: pointer;

            }

            #noticepin-widget.noticepin-bottom-right {
                right: 20px;
                bottom: 20px;
                transform: rotate(1deg);
            }

            #noticepin-widget.noticepin-bottom-left {
                left: 20px;
                bottom: 20px;
                transform: rotate(-1deg);
            }

            #noticepin-widget.noticepin-top-right {
                right: 20px;
                top: 20px;
                transform: rotate(1deg);
            }

            #noticepin-widget.noticepin-top-left {
                left: 20px;
                top: 20px;
                transform: rotate(-1deg);
            }

            @keyframes noticepin-enter {

                from {
                    opacity: 0;
                    transform:
                        translateY(15px)
                        rotate(0deg)
                        scale(.96);
                }

                to {
                    opacity: 1;
                }

            }

            @media (max-width: 600px) {

                #noticepin-widget {

                    width:
                        calc(100vw - 30px);

                    max-width: 320px;

                    padding: 19px;

                }

                #noticepin-widget.noticepin-bottom-right,
                #noticepin-widget.noticepin-bottom-left {

                    left: 15px;
                    right: 15px;
                    bottom: 15px;

                    width:
                        calc(100vw - 30px);

                }

                #noticepin-widget.noticepin-top-right,
                #noticepin-widget.noticepin-top-left {

                    left: 15px;
                    right: 15px;
                    top: 15px;

                    width:
                        calc(100vw - 30px);

                }

            }

        `;

        document.head.appendChild(style);
    }

    function createWidget(notice) {

        loadStyles();

        const existing =
            document.getElementById(
                'noticepin-widget'
            );

        if (existing) {
            existing.remove();
        }

        const widget =
            document.createElement('div');

        widget.id =
            'noticepin-widget';

        widget.className =
            'noticepin-' +
            notice.position;

        widget.style.background =
            notice.note_color;

        widget.style.color =
            notice.text_color;

        const pin =
            document.createElement('div');

        pin.className =
            'noticepin-pin';

        pin.textContent = '📌';

        widget.appendChild(pin);

        const close =
            document.createElement('button');

        close.className =
            'noticepin-close';

        close.setAttribute(
            'aria-label',
            'Close notice'
        );

        close.innerHTML = '×';

        close.onclick = function () {
            widget.remove();
        };

        widget.appendChild(close);

        const title =
            document.createElement('div');

        title.className =
            'noticepin-title';

        title.textContent =
            notice.title;

        widget.appendChild(title);

        const message =
            document.createElement('div');

        message.className =
            'noticepin-message';

        message.textContent =
            notice.message;

        widget.appendChild(message);

        if (
            notice.button_text &&
            notice.button_url
        ) {

            const button =
                document.createElement('a');

            button.className =
                'noticepin-button';

            button.textContent =
                notice.button_text;

            button.href =
                notice.button_url;

            button.target =
                '_blank';

            button.rel =
                'noopener noreferrer';

            button.onclick =
                function () {

                    trackClick(
                        notice.id
                    );

                };

            widget.appendChild(button);
        }

        document.body.appendChild(widget);
    }

    function trackClick(noticeId) {

        const data =
            new FormData();

        data.append(
            'notice_id',
            noticeId
        );

        fetch(
            baseUrl + '/api/track.php',
            {
                method: 'POST',
                body: data,
                keepalive: true
            }
        ).catch(function () {});
    }

    function loadNotice() {

        fetch(apiUrl)

            .then(function (response) {
                return response.json();
            })

            .then(function (data) {

                if (
                    data.success &&
                    data.notice
                ) {

                    createWidget(
                        data.notice
                    );

                }

            })

            .catch(function (error) {

                console.warn(
                    'NoticePin failed to load.',
                    error
                );

            });
    }

    if (
        document.readyState === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            loadNotice
        );

    } else {

        loadNotice();

    }

})();