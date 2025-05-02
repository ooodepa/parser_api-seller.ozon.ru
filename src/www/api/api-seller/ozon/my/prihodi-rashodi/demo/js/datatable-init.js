new DataTable("table.table", {
  ajax: {
    url: "/api/api-seller/ozon/my/prihodi-rashodi/",
    type: "GET",
  },
  order: [[0, "desc"]],
  layout: {
    topStart: {
      buttons: ["copy", "csv", "excel", "pdf", "print"],
    },
    topEnd: [
      "search", // поле поиска
    ],
    bottomStart: [
      "info", // информация о количестве записей
      "pageLength", // выбор количества в выборке
    ],
    bottomEnd: [
      "paging", // пагинация
    ],
  },
  processing: true, // Показывать три точки как лоадер загрузки
  language: {
    url: "/assets/cdn.datatables.net/plug-ins/1.11.5/i18n/ru.json",
  },
  lengthMenu: [
    [-1, 10, 25, 50],
    ["ВСЕ", "10", "25", "50"],
  ],
  columns: [
    {
      title: "№ п/п",
      data: "n",
    },
    {
      title: "Приход, рос. руб.",
      data: "prihod",
    },
    {
      title: "Расход, рос. руб.",
      data: "rashod",
    },
    {
      title: "Итого, рос. коп.",
      data: "itogo",
    },
    {
      title: "Итого, рос. руб.",
      data: "itogo_2",
    },
    {
      title: "",
      data: "_",
    },
    {
      title: "За что начисление (код)",
      data: "comment_kod",
    },
    {
      title: "За что начисление (наименование)",
      data: "comment_ru",
    },
    {
      title: "№ п/п транзакции",
      data: "i",
    },
    {
      title: "Идентификатор транзакции",
      data: "comment_operation_id",
    },
    {
      title: "Наименование транзакции (Тип начисления)",
      data: "comment_operation_type_name",
    },
    {
      title: "Дата транзакции (Дата начисления)",
      data: "comment_operation_date",
    },
    {
      title:
        "Дата заказа (Дата принятия заказа в обработку или оказания услуги)",
      data: "comment__posting_order_date",
    },
    {
      title: "Номер заказа (Номер отправления или идентификатор услуги)",
      data: "comment__posting_posting_number",
    },
    {
      title: "Идентификатор пользователя",
      data: "comment__posting_posting_number_1",
    },
    {
      title: "Номер заказа у пользователя",
      data: "comment__posting_posting_number_2",
    },
    {
      title: "Номер заказа за текущий день",
      data: "comment__posting_posting_number_3",
    },
    {
      title: "Артикулы OZON",
      data: "comment__items_sku",
    },
  ],
  scrollCollapse: true,
  scrollY: 400,
});
