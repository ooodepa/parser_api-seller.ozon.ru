new DataTable("table.table", {
  ajax: {
    url: "/api/api-seller/ozon/my/products/",
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
  processing: true, // показывать три точки как лоадер загрузки
  language: {
    url: "/assets/cdn.datatables.net/plug-ins/1.11.5/i18n/ru.json",
  },
  lengthMenu: [
    [-1, 10, 25, 50],
    ["ВСЕ", "10", "25", "50"],
  ],
  columns: [
    {
      title: "Артикул",
      data: "PRODUCT_CODE",
    },
    {
      title: "Картинка",
      data: "PRIMARY_IMAGE",
      render: function (data, type, row) {
        if (data && type === "display") {
          return `<img src="${data}" style="max-height: 50px; max-width: 50px;" />`;
        }
        return data;
      },
    },
    {
      title: "Наименование",
      data: "PRODUCT_NAME",
    },
    {
      title: "ID",
      data: "PRODUCT_ID",
    },
  ],
  scrollCollapse: true,
  scrollY: 400,
  initComplete: function () {
    this.api()
      .columns()
      .every(function () {
        let column = this;
        let header = $(column.header());
        let title = header.text().trim();

        let input = $(
          '<input type="text" placeholder="Поиск ' +
            title +
            '" style="width:100%"/>'
        )
          .appendTo(header)
          .on("keyup change", function () {
            if (column.search() !== this.value) {
              column.search(this.value).draw();
            }
          })
          .on("click", function (e) {
            e.stopPropagation(); // предотвращаю событие сортировка
          });
      });
  },
});
