class FinanseTransactionList {
  static async innerHtml() {
    const DIV = document.getElementById("root");
    try {
      const ARRAY_TRANSACTIONS = await FinanseTransactionList.fetch();

      let html = "";
      html += '<table class="table table-borderer table-striped">';
      html += `
                <thead class="table-light">
                    <tr>
                        <th>№ п/п</th>
                        <th>Дата заказа</th>
                        <th>Номер заказа</th>
                        <th>Номер заказа полный</th>
                    </tr>
                </thead>
            `;
      html += "<tbody>";

      const UNIQ_POSTING_ARRAY = [];
      let count_postings = 0;
      for (let i = 0; i < ARRAY_TRANSACTIONS.length; ++i) {
        const CURRENT = ARRAY_TRANSACTIONS[i];

        // const ORDER_DATE = CURRENT.posting.order_date;
        const ORDER_DATE = CURRENT._posting_order_date;

        if (`${ORDER_DATE}`.length == 0) {
          continue;
        }

        // const POSTING_NUMBER = CURRENT.posting.posting_number;
        const POSTING_NUMBER = CURRENT._posting_posting_number;
        const POSTING_NUMBER_PARTS = POSTING_NUMBER.split("-");
        const POSTING_NUMBER_PART1 = POSTING_NUMBER_PARTS[0];
        const POSTING_NUMBER_PART2 = POSTING_NUMBER_PARTS[1];
        const POSTING_NUMBER_PART3 = POSTING_NUMBER_PARTS[2];

        const ARRAY_ELEMENT = `${ORDER_DATE}\n${POSTING_NUMBER_PART1}\n${POSTING_NUMBER_PART2}`;
        if (UNIQ_POSTING_ARRAY.includes(ARRAY_ELEMENT)) {
          continue;
        }

        count_postings += 1;
        UNIQ_POSTING_ARRAY.push(ARRAY_ELEMENT);

        let full_posting_number = "";
        for (let j = 0; j < ARRAY_TRANSACTIONS.length; j++) {
          const CURRENT_J = ARRAY_TRANSACTIONS[j];

          // if (CURRENT_J.posting.order_date != ORDER_DATE) {
          if (CURRENT_J._posting_order_date != ORDER_DATE) {
            continue;
          }

          // const POSTING_NUMBER_J = `${CURRENT_J.posting.posting_number}`;
          const POSTING_NUMBER_J = `${CURRENT_J._posting_posting_number}`;
          if (!POSTING_NUMBER_J.startsWith(POSTING_NUMBER)) {
            continue;
          }

          if (`${POSTING_NUMBER_J}`.split("-").length != 3) {
            continue;
          }

          full_posting_number = POSTING_NUMBER_J;
        }

        html += `
                    <tr>
                        <td>${count_postings}</td>
                        <td>${ORDER_DATE}</td>
                        <td>${POSTING_NUMBER}</td>
                        <td>${full_posting_number}</td>
                    </tr>
                `;
      }
      html += "</tbody>";
      html += "</table>";

      DIV.innerHTML = html;
    } catch (exception) {
      console.error(exception);
      DIV.innerHTML = `<p class="text-danger">${exception}</p>`;
    }
  }

  static async fetch() {
    try {
      const URL_ = "/api/api-seller/ozon/v3/finance/transaction/list/";

      const RESPONSE = await fetch(URL_);

      const HTTP_STATUS_CODE = RESPONSE.status;
      if (HTTP_STATUS_CODE !== 200) {
        let message = "";

        try {
          const JSON_ = await RESPONSE.json();
          message = JSON_.message;
        } catch (exception) {}

        throw new Error(`HTTP status ${HTTP_STATUS_CODE} ${message}`);
      }

      const JSON_ = await RESPONSE.json();
      return JSON_;
    } catch (exception) {
      console.error(exception);
      throw exception;
    }
  }
}
