new DataTable("table.table", {
  ajax: {
    url: "/api/api-seller/ozon/my/stock/",
    type: "GET",
  },
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
      title: "ID",
      data: "код_продукта_ozon",
    },
    {
      title: "Артикул",
      data: "артикул_продавца",
    },
    {
      title: "SKU",
      data: "sku",
    },
    {
      title: "В архиве?",
      data: "в_архиве",
    },
    {
      title: "Наименование",
      data: "наименование",
    },
    {
      title: "Штрихкод",
      data: "штрихкод",
    },
    {
      title: "НДС",
      data: "НДС",
    },
    {
      title: "Цена маркетинговая",
      data: "цена_маркетинговая",
    },
    {
      title: "Цена минимальная",
      data: "цена_минимальная",
    },
    {
      title: "Цена зачеркнутая",
      data: "цена_зачеркнутая",
    },
    {
      title: "Цена",
      data: "цена",
    },
    {
      title: "X",
      data: "X",
    },
    {
      title: "Y",
      data: "Y",
    },
    {
      title: "Z",
      data: "Z",
    },
    {
      title: "Вес",
      data: "G",
    },
    {
      title: "Литры",
      data: "volume_weight",
    },
    {
      title: "Есть остатки FBO?",
      data: "есть_остатки_FBO",
    },
    {
      title: "Есть остатки FBS?",
      data: "есть_остатки_FBS",
    },
  ],
  scrollCollapse: true,
  scrollY: 400,
});
