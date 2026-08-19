// Cloudflare Pages Function: shared "hearts for Lincoln" counter.
//   GET  /api/hearts  -> { count }              (read the global total)
//   POST /api/hearts  -> increments, { count }  (add one, return new total)
//
// Setup (one time, in the Cloudflare dashboard):
//   1. Workers & Pages -> KV -> Create namespace, e.g. "lincoln_hearts".
//   2. Your Pages project -> Settings -> Functions -> KV namespace bindings ->
//      Add binding: Variable name = HEARTS, KV namespace = lincoln_hearts.
//   3. Redeploy. The counter is then shared across every visitor.
export async function onRequest(context) {
  const { request, env } = context;
  const kv = env.HEARTS;
  const KEY = "lincoln";
  const headers = {
    "Content-Type": "application/json",
    "Access-Control-Allow-Origin": "*",
    "Access-Control-Allow-Methods": "GET, POST, OPTIONS",
    "Cache-Control": "no-store",
  };

  if (request.method === "OPTIONS") return new Response(null, { headers });
  if (!kv) return new Response(JSON.stringify({ error: "kv-not-bound" }), { status: 500, headers });

  let count = parseInt((await kv.get(KEY)) || "0", 10);
  if (!Number.isFinite(count)) count = 0;

  if (request.method === "POST") {
    count += 1;
    await kv.put(KEY, String(count));
  }

  return new Response(JSON.stringify({ count }), { headers });
}
