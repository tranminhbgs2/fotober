<?php

namespace App\Helpers;

class Constants
{
    const CRM_DOMAIN = 'http://orders.fotober.com';

    // Khai báo bảng CSDL
    const TABLE_ADS = 'ads';
    const TABLE_CONFIGS = 'configs';
    const TABLE_COUNTRIES = 'countries';
    const TABLE_CUSTOMERS = 'customers';
    const TABLE_FAILED_JOBS = 'failed_jobs';
    const TABLE_GROUP_ROLE = 'group_role';
    const TABLE_GROUPS = 'groups';
    const TABLE_JOBS = 'jobs';
    const TABLE_LIBRARIES = 'libraries';
    const TABLE_LOG_ACTIONS = 'log_actions';
    const TABLE_LOG_AUTHS = 'log_auths';
    const TABLE_LOG_FOLLOWS = 'log_follows';
    const TABLE_MESSAGES = 'messages';
    const TABLE_MIGRATIONS = 'migrations';
    const TABLE_NOTIFICATION_LOGS = 'notification_logs';
    const TABLE_NOTIFICATIONS = 'notifications';
    const TABLE_ORDERS = 'orders';
    const TABLE_PASSWORD_RESETS = 'password_resets';
    const TABLE_PAYMENT_DETAIL = 'payment_detail';
    const TABLE_PAYMENTS = 'payments';
    const TABLE_PERMISSIONS = 'permissions';
    const TABLE_REQUIREMENTS = 'requirements';
    const TABLE_ROLES = 'roles';
    const TABLE_SERVICES = 'services';
    const TABLE_USER_PERMISSION = 'user_permission';
    const TABLE_USERS = 'users';
    const TABLE_OUTPUTS = 'outputs';
    const TABLE_INPUTS = 'inputs';
    const TABLE_KPIS = 'kpis';


    const LANGUAGE_LIST = 'ENGLISH,VIETNAMESE';
    const LANGUAGE_DEFAULT = 'en';
    const ASSET_VERSION = '1.0.1';

    const PLATFORM = 'ios,iOS,android,Android,web,Web';

    const ACCOUNT_TYPE = 'CUSTOMER,SALE,ADMIN,EDITOR,QAQC,SUPER_ADMIN';

    const ACCOUNT_TYPE_CUSTOMER = 'CUSTOMER';
    const ACCOUNT_TYPE_SALE = 'SALE';
    const ACCOUNT_TYPE_ADMIN = 'ADMIN';
    const ACCOUNT_TYPE_EDITOR = 'EDITOR';
    const ACCOUNT_TYPE_QAQC = 'QAQC';
    const ACCOUNT_TYPE_SUPER_ADMIN = 'SUPER_ADMIN';
    const ACCOUNT_TYPE_STAFF = 'STAFF';

    const ACTION_TYPE_LOGIN = 'LOGIN';
    const ACTION_TYPE_LOGOUT = 'LOGOUT';

    const USER_STATUS_NEW = 0;
    const USER_STATUS_ACTIVE = 1;
    const USER_STATUS_LOCKED = 2;
    const USER_STATUS_DELETED = 3;

    const ORDER_STATUS_DRAFT = 0;
    const ORDER_STATUS_NEW = 1;
    const ORDER_STATUS_PENDING = 2;
    const ORDER_STATUS_EDITING = 3;
    const ORDER_STATUS_EDITED = 4;
    const ORDER_STATUS_CHECKING = 5;
    const ORDER_STATUS_CHECKED = 6;
    const ORDER_STATUS_DELIVERING = 7;  // Sale update để Cus kiểm tra
    const ORDER_STATUS_COMPLETED = 8;   // Cus ok thì Sale update
    const ORDER_STATUS_REDO = 9;        // Cus ko ok thì Sale update
    const ORDER_STATUS_AWAITING_PAYMENT = 10;
    const ORDER_STATUS_PAID = 11;
    const ORDER_STATUS_DELETED = 12;

    const PAYMENT_STATUS_NEW = 0;
    const PAYMENT_STATUS_PENDING = 1;
    const PAYMENT_STATUS_SUCCESS = 2;
    const PAYMENT_STATUS_FALIED = 3;

    const MESSAGE_STATUS_RECOVER = 0;
    const MESSAGE_STATUS_SHOW = 1;
    const MESSAGE_STATUS_EDITED = 2;
    const MESSAGE_STATUS_DELETED = 3;

    const MESSAGE_TYPE_TEXT = 'TEXT';
    const MESSAGE_TYPE_ICON = 'ICON';
    const MESSAGE_TYPE_LINK = 'LINK';
    const MESSAGE_TYPE_IMAGE = 'IMAGE';
    const MESSAGE_TYPE_FILE = 'FILE';

    const UPLOAD_IMAGE_CHAT = 'public/uploads/message';
    const UPLOAD_IMAGE_ORDER = 'public/uploads/order';
    const UPLOAD_IMAGE_AVATAR = 'public/uploads/avatar';
    const UPLOAD_IMAGE_OUTPUT = 'public/uploads/output';

    const VIEW_PAGE_PATH = 'themes.cms.ace.pages';
    const VIEW_LAYOUT_PATH = 'themes.cms.ace.layouts';

    //Thông số của Paypal
    //account cua a Hoàng
    const PAYPAL_CLIENT_ID = 'AVzwVZm-q-Hd3FpiKDGjF4VyhZG-9rJ3IpUBVQBGtQxmUVpNLAquGJchqZ5z070bscPvk9XOjbYYVV5n';
    const PAYPAL_CLIENT_SECRET = 'EI9--ZPOWqSkne1Pvp9GyqmWd-KBhm3VAkoU-VkIUTai6W_QH9qaXouJ77BB27_2fyCK3NwdNK3bpoL1';
    //account dùng để test
    // const PAYPAL_CLIENT_ID = 'AUYWdpmYToTR-FtgYXBsRoy9zUHkTIcvb_pJ5rTEtL7a_yp1veCMwvSTNb4UBiY0D2jDX-_7Wioi2jZW';
    // const PAYPAL_CLIENT_SECRET = 'EJQKA1UWrIB2ap6xmicWadzNmaQdaq0Ly4fHMrkdONj3AqbPdWaRYp4QcJ1MVT-LKJYvdEu5JuTFg8pr';
    //account paypal minh
    // const PAYPAL_CLIENT_ID = 'AQCvkCaIcjQM3oNDjmZDsLqH-Cp3yJ1Y0jtLqj_B86etDnvdj0ay6Nts09wNT3lVIDZ5xgBhbrOEQCDD';
    // const PAYPAL_CLIENT_SECRET = 'EIJwaCbDCGso9Edq6BDx1tB5Rbc3PlGZLHVIsiZe9dk0eq3zCPc-7xkoUHwJUFz5bGLJRunvuR_J_pcH';

    //Thông tin của hệ thống
    // const EMAIL = 'sb-9xwxk6021531@business.example.com'; //mail test
    // const EMAIL = 'nhokbghotboi@gmail.com'; //mail live minh
    const EMAIL = 'invoice@fotober.com'; //mail live a Hoàng
    const ADDESS = 'fotober.com';
    const STATE = 'Thanh Xuân';
    const CITY = 'Hà Nội';
    const COUNTRY = 'VN';
    const POSTALCODE = '10000';
    const PHONE = '326786633';
    const COMPANY_NAME = 'Fotober Vitenam';
    const FIRST_NAME = 'Hoang';
    const LAST_NAME = 'Dang';

    const EMAIL_ORDER_CREATE = 'ORDER_CREATE';
    const EMAIL_ORDER_DELIVERY = 'ORDER_DELIVERY';
    const EMAIL_ORDER_COMPLETED = 'ORDER_COMPLETED';
    const EMAIL_ORDER_AWAIT_PAYMENT = 'ORDER_AWAIT_PAYMENT';
    const EMAIL_CHAT_MESSAGE = 'CHAT_MESSAGE';
    const EMAIL_ACCOUNT_ACTIVATION = 'ACCOUNT_ACTIVATION';
    const EMAIL_SUPPORT_FOTOBER = 'SUPPORT_FOTOBER';
    const EMAIL_TYPE_FORGOT_PASSWORD = 'EMAIL_TYPE_FORGOT_PASSWORD';
    const ORDER_UPDATE_REVISION = 'ORDER_UPDATE_REVISION';
    const EMAIL_ORDER_ASSIGN_SALE = 'EMAIL_ORDER_ASSIGN_SALE';
    const EMAIL_ORDER_REQUEST_OUTPUT = 'EMAIL_ORDER_REQUEST_OUTPUT';
    const EMAIL_ORDER_ACCEPT_OUTPUT = 'EMAIL_ORDER_ACCEPT_OUTPUT';

    const DEFAULT_AVATAR = 'storage/uploads/avatar/user-avatar-male.jpg';
    const DEFAULT_LOGO_INVOICE = 'storage/uploads/avatar/logo-invoice.jpg';

    const SSH_GROUP = 'root';
    const SSH_USER = 'root';

    const UPLOAD_MIMES_TYPE = 'doc,pdf,docx,zip,jpeg,jpg,png';
    const UPLOAD_MAX_SIZE = 15360;  //15360 ~ 15MB

    const ORDER_NEWEST = 'newest';
    const ORDER_OLDEST = 'oldest';

    const PERMISSION_DENIED = 403;

    const SERVICE_GROUP_ALL_SERVICES = 0;
    const SERVICE_GROUP_PHOTO_EDITING = 1;
    const SERVICE_GROUP_VIRTUAL_STAGING = 2;
    const SERVICE_GROUP_VIDEO_EDITING = 3;
    const SERVICE_GROUP_ARCHITECTURE_PLANNING = 4;

    /* Loại dịch vụ theo loại xử lý là ảnh/video */
    const SERVICE_TYPE_BEFORE_AFTER = 'BEFORE_AFTER';
    const SERVICE_TYPE_ONLY_VIDEO = 'ONLY_VIDEO';
    const SERVICE_TYPE_ONLY_IMAGE = 'ONLY_IMAGE';

    /* Nguồn video theo url */
    const VIDEO_SRC_VIMEO = 'VIMEO';
    const VIDEO_SRC_YOUTUBE = 'YOUTUBE';
    const VIDEO_SRC_FOTOBER = 'FOTOBER';

    /* Đường dẫn cơ bản nhúng video */
    const VIMEO_EMBED_BASE_URL = 'https://player.vimeo.com/video/';
    const YOUTUBE_EMBED_BASE_URL = 'https://www.youtube.com/embed/';









}
