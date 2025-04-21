class SwaggerUI__Helper {
  static getUrl() {
    const SELECT = document.getElementById("swagger_ui__select");
    const _URL = SELECT.value;
    return _URL;
  }

  static innerHtmlSwaggerUI() {
    try {
      const _URL = SwaggerUI__Helper.getUrl();
      window.ui = SwaggerUIBundle({
        url: _URL,
        dom_id: "#swagger-ui",
      });
    } catch (exception) {
      console.error(exception);
    }
  }
}

window.onload = () => {
  SwaggerUI__Helper.innerHtmlSwaggerUI();
};
