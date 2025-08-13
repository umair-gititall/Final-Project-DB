const express = require("express");
const cors = require("cors");
const emailCheck = require("email-check");

const app = express();
app.use(cors());
app.use(express.json());

app.post("/validate", async (req, res) => {
  const { email } = req.body;
  if (!email) {
    return res
      .status(400)
      .json({ valid: false, message: "Email required~! 😭" });
  }

  try {
    const exists = await emailCheck(email);
    res.json({ valid: exists });
  } catch (err) {
    console.error(err);
    res.status(500).json({ valid: false, message: "Validation error 😣" });
  }
});

app.listen(3001, () => {
  console.log("🌐 Email-check server running on port 3001 💖");
});
