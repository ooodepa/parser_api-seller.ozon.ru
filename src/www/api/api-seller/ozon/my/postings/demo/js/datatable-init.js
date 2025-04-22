new DataTable("table.table", {
  ajax: {
    url: "/api/api-seller/ozon/my/postings/",
    type: "GET",
  },
  order: [
    [0, 'desc'],
  ],
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
      title: "Дата",
      data: "posting_date",
    },
    {
      title: "Идентификатор пользователя",
      data: "p1",
    },
    {
      title: "Номер заказа пользователя",
      data: "p2",
    },
    {
      title: "Номер заказа в текущий день",
      data: "p3",
    },
    {
      title: "FBO/FBS",
      data: "fb_type",
    },
  ],
  scrollCollapse: true,
  scrollY: 400,
});
