class VdMessage {
  constructor() {
    this.iconMap = {
      success: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>`,
      warning: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
                </svg>`,
      error: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
              </svg>`,
      info: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
              <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
            </svg>`,
    };

    this.messages = [];
    this.maxMessages = 2;
    this.insertMessageContainer();
    this.insertCSS();
  }

  insertMessageContainer() {
    // Tạo và chèn thẻ div với class 'vd_message_js' vào body nếu chưa tồn tại
    if ($(".vd_message_js").length === 0) {
      const messageContainer = $('<div class="vd_message_js"></div>');
      $("body").append(messageContainer);
    }
  }

  insertCSS() {
    // Thêm CSS trực tiếp vào trang nếu cần
    const css = `
            .vd_message_js {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2010;
            transition: 0.3s;
            position: fixed;
            }

            .vd_message {
            padding: 9px 12px;
            border-radius: 12px;
            box-shadow: 0 6px 16px 0 rgba(0, 0, 0, 0.08),
                0 3px 6px -4px rgba(0, 0, 0, 0.12), 0 9px 28px 8px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
            transition: 0.3s;
            width: auto;
            position: absolute;
            white-space: nowrap;
            }

            .vd_message_main {
            display: flex;
            gap: 5px;
            align-items: center;
            }

            .vd_message_noti {
            color: rgba(0, 0, 0, 0.88);
            font-size: 14px;
            line-height: 1.5714285714285714;
            list-style: none;
            pointer-events: none;
            }

            .vd_message_icon {
              line-height: 0;
            }

            /* status */
            .vd_message.success .vd_message_icon {
              color: #52c41a;
            }

            .vd_message.error .vd_message_icon {
              color: red;
            }

            .vd_message.warning .vd_message_icon {
              color: orange;
            }

            .vd_message.info .vd_message_icon {
              color: #0d6efd;
            }
      `;

    // Chèn style vào head
    if ($("#vd_message_style").length === 0) {
      $('<style id="vd_message_style"></style>').text(css).appendTo("head");
    }
  }

  createMessage(type, message) {
    const icon = this.iconMap[type] || this.iconMap.info;
    const htmlMessage = `
        <div class="vd_message ${type} animate__animated animate__faster animate__fadeInDown">
          <div class="vd_message_main">
            <div class="vd_message_icon">
              ${icon}
            </div>
            <div class="vd_message_noti">
              ${message}
            </div>
          </div>
        </div>
      `;
    return htmlMessage;
  }

  show(type, message, timeout = 3000) {
    const htmlMessage = this.createMessage(type, message);
    const messageContainer = $(".vd_message_js");

    if (this.messages.length >= this.maxMessages) {
      const firstMessage = this.messages.shift();
      firstMessage.remove();
    }

    const newMessage = $(htmlMessage);
    this.messages.push(newMessage);
    messageContainer.append(newMessage);

    this.updateMessagePositions();
    newMessage.fadeIn(5, function () {
      setTimeout(() => {
        $(this)
          .removeClass("animate__fadeInDown")
          .addClass("animate__fadeOutUp");
        setTimeout(() => {
          $(this).remove();
        }, 1000);
      }, timeout);
    });
  }

  updateMessagePositions() {
    const messageContainer = $(".vd_message_js");
    const messages = messageContainer.children();
    // Tạo khoảng cách giữa các thông báo, ví dụ mỗi thông báo cách nhau 3.2rem theo chiều dọc
    messages.each(function (index) {
      $(this).css({
        top: `${index * 3.2}rem`,
        position: "absolute", // Đảm bảo các thông báo được xếp chồng lên nhau và cách đều
      });
    });
  }

  destroy() {
    this.messages.forEach((message) => {
      message.removeClass("animate__fadeInDown").addClass("animate__fadeOutUp");
      setTimeout(() => {
        message.remove();
      }, 1000);
    });
  }
}

// Sử dụng thư viện
// messageVD.show("success", "This is a success message!");
// messageVD.show("error", "This is an error message!", 5000);
