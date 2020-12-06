import express from "express";

// general config
const PORT = 80;
let server = express();

server.get("/", (req, res) => {
  let respond = {
    status: 200,
    body: "Hello World",
  };

  res.header("Content-Type", "application/json");
  res.send(JSON.stringify(respond));
});

// ignite
server.listen(PORT, () => {
  console.log(`Server is now listening on port ${PORT}`);
});
