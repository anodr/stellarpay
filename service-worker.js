const CACHE_NAME = "examapp-v1";
const urlsToCache = [
  "/",
  "/index.php",
  "loginpanel/auth_login.php",
  "offline.html",
  "/css/styles.css",
  "/js/main.js",
  "/icons/icon-192.png",
  "/icons/icon-512.png"
];

// Install
self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(urlsToCache);
    })
  );
});

// Activate
self.addEventListener("activate", event => {
  event.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(
        keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
      );
    })
  );
});

// Fetch with fallback
self.addEventListener("fetch", event => {
  if (event.request.method === "GET") {
    event.respondWith(
      fetch(event.request)
        .then(response => response)
        .catch(() => caches.match(event.request).then(res => res || caches.match("/offline.html")))
    );
  } else if (event.request.method === "POST") {
    event.respondWith(
      fetch(event.request).catch(() => {
        return new Response(
          JSON.stringify({ error: "You are offline. Cannot complete request." }),
          { status: 503, headers: { "Content-Type": "application/json" } }
        );
      })
    );
  }
});

