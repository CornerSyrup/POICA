import express from "express";

// general config
const PORT = 80;
let server = express();

//#region  generic auth service
server.get("/auth/", (req, res) => {
  let respond = {
    status: 200,
    body: "Auth Service",
  };

  res.header("Content-Type", "application/json");
  res.send(JSON.stringify(respond));
});
//#endregion

// ignite
server.listen(PORT, () => {
  console.log(`Server is now listening on port ${PORT}`);
});
