function showShareTooltip(message = "Link copied to clipboard") {
  let tooltip = document.getElementById("share-tooltip");

  if (!tooltip) {
    tooltip = document.createElement("div");
    tooltip.id = "share-tooltip";
    Object.assign(tooltip.style, {
      position: "fixed",
      bottom: "20px",
      right: "20px",
      backgroundColor: "#333",
      color: "#fff",
      padding: "10px 14px",
      borderRadius: "5px",
      fontSize: "14px",
      zIndex: "9999",
      boxShadow: "0 2px 8px rgba(0,0,0,0.2)",
    });
    document.body.appendChild(tooltip);
  }

  tooltip.innerText = message;
  tooltip.style.display = "block";

  clearTimeout(tooltip._timer);
  tooltip._timer = setTimeout(() => {
    tooltip.style.display = "none";
  }, 2000);
}

async function copyLinkFallback() {
  const url = window.location.href;

  if (navigator.clipboard?.writeText) {
    await navigator.clipboard.writeText(url);
  } else {
    const el = document.createElement("textarea");
    el.value = url;
    el.setAttribute("readonly", "");
    el.style.position = "absolute";
    el.style.left = "-9999px";
    document.body.appendChild(el);
    el.select();
    document.execCommand("copy");
    document.body.removeChild(el);
  }

  showShareTooltip();
}

export function initShareButton() {
  document.querySelectorAll(".share-btn").forEach(btn => {
    btn.addEventListener("click", async (e) => {
      e.preventDefault();

      if (navigator.share) {
        try {
          await navigator.share({
            title: document.title,
            url: window.location.href,
          });
        } catch {
          // User cancel → không làm gì
        }
      } else {
        await copyLinkFallback();
      }
    });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  initShareButton();

  // TOC active state
  const tocLinks = document.querySelectorAll(".detail-blog #ez-toc-container a");
  tocLinks.forEach(link => {
    link.addEventListener("click", () => {
      tocLinks.forEach(l => l.classList.remove("clicked"));
      link.classList.add("clicked");
    });
  });
});