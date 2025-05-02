try {
  const TITLE = document.querySelector("title");

  const D = new Date();

  const YYYY = D.getFullYear();
  const MM = `${D.getMonth() + 1}`.padStart(2, "0");
  const DD = `${D.getDate()}`.padStart(2, "0");

  const HH = `${D.getHours()}`.padStart(2, "0");
  const II = `${D.getMinutes()}`.padStart(2, "0");
  const SS = `${D.getSeconds()}`.padStart(2, "0");

  const DATE_TIME = `${YYYY}-${MM}-${DD} ${HH}:${II}:${SS}`;

  TITLE.innerHTML = `Приход и расход (сгенерирован ${DATE_TIME})`;
} catch (exception) {
  console.error(exception);
}
