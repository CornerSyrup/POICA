export default class MailAccount {
  private alias: string = "";
  private address: string = "";

  constructor(alias: string, address: string) {
    this.Alias = alias;
    this.Address = address;
  }

  public get Alias(): string {
    return this.alias;
  }

  public set Alias(value: string) {
    this.alias = value;
  }

  public get Address(): string {
    return this.address;
  }

  public set Address(value: string) {
    if (!value.match(/\w+@\w+\.\w+/)) {
      throw "Incorrect email format";
    }

    this.address = value;
  }

  public get AccountString() {
    return `${this.Alias} <${this.address}>`;
  }
}
