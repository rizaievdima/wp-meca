/**
 * EU-kontroll lookup form ([fram_eu_kontroll]).
 *
 * Without a connected lookup provider (data-configured="0") the Statens
 * vegvesen page opens right away, inside the submit handler so popup
 * blockers allow it. Otherwise the theme REST endpoint is queried and the
 * vehicle data is shown as a table with a "Bestill time" button.
 *
 * That button stores the number in localStorage before the browser follows
 * it; the booking form ([fram_form slug="bestill-time"], which loads this
 * script too) then gets the "EU-kontroll" service ticked and the number
 * filled in, right away on the same page or after loading another one.
 * The number stays stored, so the form is filled in on later visits too.
 */
(() => {
  const VALID = /^[A-ZÆØÅ0-9]{2,9}$/u;
  const STORAGE_KEY = "fram-eu-kontroll-regnr";
  // Value of the service checkbox in the booking form.
  const SERVICE = "EU-kontroll";

  // localStorage can be unavailable (private mode, blocked storage).
  const storage = {
    get() {
      try {
        return window.localStorage.getItem(STORAGE_KEY);
      } catch (error) {
        return null;
      }
    },
    set(value) {
      try {
        window.localStorage.setItem(STORAGE_KEY, value);
      } catch (error) {}
    },
  };

  // Ticks the service and fills in the number in the booking form on this page.
  const fillBooking = (regnr = storage.get()) => {
    const service =
      regnr &&
      document.querySelector(
        `.wpcf7-form input[type="checkbox"][value="${SERVICE}"]`,
      );
    if (!service) {
      return;
    }

    const field = service.form.querySelector('input[name="regnr"]');
    service.checked = true;
    if (field) {
      field.value = regnr;
    }

    // Lets Contact Form 7 update its validation state.
    [service, field].forEach(
      (input) =>
        input && input.dispatchEvent(new Event("change", { bubbles: true })),
    );
  };

  const normalize = (value) => value.toUpperCase().replace(/[\s-]+/g, "");

  const show = (form, text, isError = false) => {
    const out = form.querySelector(".fram-eu-kontroll__result");
    out.textContent = text;
    out.classList.toggle("is-error", isError);
  };

  const element = (tag, className, text) => {
    const node = document.createElement(tag);
    if (className) {
      node.className = className;
    }
    if (text) {
      node.textContent = text;
    }
    return node;
  };

  // Rows come from the REST endpoint as { key, label, value }; the deadline row is highlighted.
  const showTable = (form, data) => {
    const table = element("dl", "fram-eu-kontroll__table");
    data.rows.forEach((row) => {
      const item = element("div", row.key === "deadline" ? "is-deadline" : "");
      item.append(element("dt", "", row.label), element("dd", "", row.value));
      table.append(item);
    });

    const booking = element(
      "a",
      "wp-element-button fram-eu-kontroll__booking",
      form.dataset.msgBooking,
    );
    booking.href = form.dataset.bookingUrl;
    booking.dataset.regnr = data.regnr;

    const out = form.querySelector(".fram-eu-kontroll__result");
    out.classList.remove("is-error");
    out.replaceChildren(
      element("p", "fram-eu-kontroll__title", form.dataset.msgTitle),
      table,
      booking,
    );
  };

  const formatDate = (iso) => {
    const date = new Date(iso);
    return Number.isNaN(date.getTime())
      ? iso
      : date.toLocaleDateString("nb-NO");
  };

  const openFallback = (form) => {
    window.open(form.dataset.fallback, "_blank", "noopener");
    show(form, form.dataset.msgFallback);
  };

  document.addEventListener("submit", async (event) => {
    const form = event.target.closest(".fram-eu-kontroll");
    if (!form) {
      return;
    }

    event.preventDefault();

    const regnr = normalize(form.elements.regnr.value);
    if (!regnr) {
      show(form, form.dataset.msgEmpty, true);
      return;
    }
    if (!VALID.test(regnr)) {
      show(form, form.dataset.msgInvalid, true);
      return;
    }

    if (form.dataset.configured !== "1") {
      openFallback(form);
      return;
    }

    const button = form.querySelector("button");
    button.disabled = true;
    show(form, form.dataset.msgLoading);

    try {
      const url = new URL(form.dataset.endpoint);
      url.searchParams.set("regnr", regnr);

      const response = await fetch(url, {
        headers: { Accept: "application/json" },
      });
      const data = await response.json();

      // 404: no vehicle with this number; the endpoint's message says so.
      if (response.status === 404 && data.message) {
        show(form, data.message, true);
        return;
      }
      if (!response.ok) {
        throw new Error(data.message || response.statusText);
      }

      if (!data.configured) {
        openFallback(form);
      } else if (data.rows && data.rows.length) {
        showTable(form, data);
      } else if (data.deadline) {
        show(
          form,
          form.dataset.msgResult.replace("%s", formatDate(data.deadline)),
        );
      } else {
        show(form, form.dataset.msgError, true);
      }
    } catch (error) {
      show(form, form.dataset.msgError, true);
    } finally {
      button.disabled = false;
    }
  });

  // "Bestill time" under a result: the number is stored before the browser follows the link,
  // and the form is filled in at once when it is on this page.
  document.addEventListener("click", (event) => {
    const link = event.target.closest(".fram-eu-kontroll__booking");
    if (link) {
      storage.set(link.dataset.regnr);
      fillBooking(link.dataset.regnr);
    }
  });

  // Arriving from a lookup on another page.
  fillBooking();
})();
