import styled from "styled-components";
import {DEFAULT_FONT, MOBILE_MAX_WIDTH, PageTitle} from "../../Constants";


const StaticContactInfo = styled.h2`
  ${DEFAULT_FONT};
  margin: 0 auto;
  font-size: 20px;
  
  ${MOBILE_MAX_WIDTH} {
    font-size: 15px;
  }
`;

function Contact() {
    return (
        <>
            <PageTitle>Contact Us</PageTitle>
            <StaticContactInfo>Email: admin@test.com</StaticContactInfo>
            <br />
            <br />
            <br />
            <StaticContactInfo>Phone: 00000 000 000</StaticContactInfo>
        </>
    )
}

export default Contact